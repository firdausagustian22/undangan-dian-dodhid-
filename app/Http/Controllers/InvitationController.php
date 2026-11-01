<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Wish;
use App\Models\Rsvp;
use App\Models\Visit;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvitationController extends Controller
{
    /**
     * Show wedding invitation by slug or query param ?to=
     */
    public function show(Request $request, ?string $slug = null)
    {
        $guest = null;

        if ($slug) {
            $guest = Guest::where('slug', $slug)->first();
        }

        if (!$guest && $request->filled('to')) {
            $toParam = trim($request->to);
            // Search by slug or exact name
            $guest = Guest::where('slug', $toParam)
                ->orWhere('name', $toParam)
                ->first();

            // If not found in database, create guest so it is tracked
            if (!$guest && $toParam !== 'Nama Tamu') {
                $guest = Guest::createWithUniqueSlug([
                    'name' => $toParam,
                    'category' => 'Teman',
                    'rsvp_status' => 'pending',
                ]);
            }
        }

        // Track visit if guest is identified
        if ($guest) {
            $isFirstOpen = is_null($guest->opened_at);

            $guest->increment('views_count');
            $now = Carbon::now();

            if ($isFirstOpen) {
                $guest->opened_at = $now;
                ActivityLog::record(
                    'view',
                    "{$guest->name} baru saja membuka link undangan untuk pertama kali! 🎉",
                    ['guest_id' => $guest->id]
                );
            }
            $guest->last_opened_at = $now;
            $guest->save();

            // Record visit log
            Visit::create([
                'guest_id' => $guest->id,
                'guest_name' => $guest->name,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string)$request->userAgent(), 0, 255),
                'referer' => substr((string)$request->header('referer'), 0, 255),
                'created_at' => $now,
            ]);
        }

        // Fetch wishes from database
        $wishes = Wish::where('is_approved', true)
            ->orderByDesc('is_pinned')
            ->latest()
            ->take(50)
            ->get();

        return view('invitation.show', compact('guest', 'wishes'));
    }

    /**
     * Backend Bridge for public/undangan frontend (RSVP, Wishes, Gift)
     */
    public function backendBridge(Request $request)
    {
        // 1. GET Request: Load wishes
        if ($request->isMethod('GET')) {
            $wishes = Wish::where('is_approved', true)
                ->orderByDesc('is_pinned')
                ->latest()
                ->get()
                ->map(function ($w) {
                    $attMap = [
                        'attending' => 'Hadir',
                        'declined' => 'Tidak Hadir',
                        'tentative' => 'Ragu-ragu',
                    ];
                    return [
                        'id' => (string) $w->id,
                        'parentId' => null,
                        'name' => $w->name,
                        'message' => $w->message,
                        'attendance' => $attMap[$w->attendance] ?? $w->attendance,
                        'date' => $w->created_at->toIso8601String(),
                    ];
                });

            return response()->json([
                'ok' => true,
                'data' => $wishes,
            ]);
        }

        // 2. POST Request: Handle RSVP / Wish / Gift
        $raw = $request->getContent();
        $payload = json_decode($raw, true) ?: $request->all();
        $type = $payload['type'] ?? 'rsvp';

        if ($type === 'rsvp') {
            $name = trim($payload['nama'] ?? $payload['name'] ?? 'Tamu Undangan');
            $kehadiranRaw = strtolower($payload['kehadiran'] ?? $payload['status'] ?? 'hadir');
            
            $status = 'attending';
            if (str_contains($kehadiranRaw, 'tidak') || str_contains($kehadiranRaw, 'absen') || str_contains($kehadiranRaw, 'decline')) {
                $status = 'declined';
            } elseif (str_contains($kehadiranRaw, 'ragu') || str_contains($kehadiranRaw, 'tentative')) {
                $status = 'tentative';
            }

            $pax = (int) ($payload['jumlah'] ?? $payload['pax'] ?? 1);
            if ($status === 'declined') {
                $pax = 0;
            }

            // Find or associate guest
            $guest = Guest::where('name', $name)->first();
            if ($guest) {
                $guest->update([
                    'rsvp_status' => $status,
                    'pax' => $pax,
                ]);
            }

            $rsvp = Rsvp::create([
                'guest_id' => $guest?->id,
                'name' => $name,
                'phone' => $guest?->phone,
                'status' => $status,
                'pax' => $pax,
                'ip_address' => $request->ip(),
            ]);

            $statusText = [
                'attending' => "Hadir ({$pax} Pax)",
                'declined' => 'Tidak Dapat Hadir',
                'tentative' => 'Ragu-ragu',
            ][$status] ?? ucfirst($status);

            ActivityLog::record(
                'rsvp',
                "{$name} mengonfirmasi RSVP via web: {$statusText}",
                ['guest_id' => $guest?->id, 'status' => $status, 'pax' => $pax]
            );

            return response()->json([
                'ok' => true,
                'message' => 'Konfirmasi kehadiran berhasil disimpan!',
                'data' => $rsvp,
            ]);
        }

        if ($type === 'wish') {
            $name = trim($payload['name'] ?? $payload['nama'] ?? 'Tamu');
            $message = trim($payload['message'] ?? $payload['ucapan'] ?? '');
            $attRaw = strtolower($payload['attendance'] ?? $payload['kehadiran'] ?? 'hadir');

            $attendance = 'attending';
            if (str_contains($attRaw, 'tidak') || str_contains($attRaw, 'absen')) {
                $attendance = 'declined';
            } elseif (str_contains($attRaw, 'ragu')) {
                $attendance = 'tentative';
            }

            $guest = Guest::where('name', $name)->first();

            $wish = Wish::create([
                'guest_id' => $guest?->id,
                'name' => $name,
                'relationship' => $guest?->category ?? 'Tamu',
                'message' => $message,
                'attendance' => $attendance,
                'is_approved' => true,
            ]);

            ActivityLog::record(
                'wish',
                "{$name} mengirim ucapan & doa restu di web",
                ['wish_id' => $wish->id]
            );

            return response()->json([
                'ok' => true,
                'message' => 'Ucapan & doa berhasil dikirim!',
                'data' => $wish,
            ]);
        }

        if ($type === 'gift') {
            $name = trim($payload['nama'] ?? 'Tamu');
            $bank = trim($payload['bank'] ?? '-');
            $nominal = trim($payload['nominal'] ?? '-');

            ActivityLog::record(
                'gift',
                "{$name} mengonfirmasi pengiriman tanda kasih / gift ({$bank} - {$nominal})"
            );

            return response()->json([
                'ok' => true,
                'message' => 'Konfirmasi tanda kasih berhasil dicatat!',
            ]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Direct RSVP fallback submission
     */
    public function submitRsvp(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'nullable|exists:guests,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:attending,declined,tentative',
            'pax' => 'required|integer|min:0|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        $guest = !empty($validated['guest_id']) ? Guest::find($validated['guest_id']) : null;
        if ($guest) {
            $guest->update([
                'rsvp_status' => $validated['status'],
                'pax' => $validated['status'] === 'attending' ? $validated['pax'] : 0,
                'notes' => $validated['notes'] ?? $guest->notes,
            ]);
        }

        $rsvp = Rsvp::create([
            'guest_id' => $guest?->id,
            'name' => $validated['name'],
            'phone' => $guest?->phone,
            'status' => $validated['status'],
            'pax' => $validated['status'] === 'attending' ? $validated['pax'] : 0,
            'notes' => $validated['notes'] ?? null,
            'ip_address' => $request->ip(),
        ]);

        ActivityLog::record(
            'rsvp',
            "{$validated['name']} mengonfirmasi RSVP: " . ucfirst($validated['status'])
        );

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'rsvp' => $rsvp]);
        }

        return redirect()->back()->with('success_rsvp', 'Terima kasih atas konfirmasi kehadiran Anda!');
    }

    /**
     * Direct Wish fallback submission
     */
    public function submitWish(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'nullable|exists:guests,id',
            'name' => 'required|string|max:255',
            'relationship' => 'nullable|string|max:100',
            'message' => 'required|string|max:1000',
            'attendance' => 'nullable|in:attending,declined,tentative',
        ]);

        $wish = Wish::create([
            'guest_id' => $validated['guest_id'] ?? null,
            'name' => $validated['name'],
            'relationship' => $validated['relationship'] ?? 'Teman',
            'message' => $validated['message'],
            'attendance' => $validated['attendance'] ?? 'attending',
            'is_approved' => true,
        ]);

        ActivityLog::record(
            'wish',
            "{$wish->name} mengirimkan ucapan & doa restu"
        );

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'wish' => $wish]);
        }

        return redirect()->back()->with('success_wish', 'Terima kasih banyak atas doa dan ucapan hangatnya!');
    }
}
