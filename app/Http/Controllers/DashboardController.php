<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Wish;
use App\Models\Visit;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. KPI & Summary Metrics
        $totalGuests = Guest::count();
        $openedGuests = Guest::whereNotNull('opened_at')->count();
        $unopenedGuests = $totalGuests - $openedGuests;
        $openedPercentage = $totalGuests > 0 ? round(($openedGuests / $totalGuests) * 100, 1) : 0;
        $totalViews = Visit::count();

        $attendingGuests = Guest::where('rsvp_status', 'attending')->count();
        $declinedGuests = Guest::where('rsvp_status', 'declined')->count();
        $tentativeGuests = Guest::where('rsvp_status', 'tentative')->count();
        $pendingGuests = Guest::where('rsvp_status', 'pending')->count();
        $totalPax = (int) Guest::where('rsvp_status', 'attending')->sum('pax');

        // Categories breakdown
        $categories = ['VIP', 'Keluarga', 'Teman', 'Rekan Kerja'];
        $categoriesStats = [];
        foreach ($categories as $cat) {
            $categoriesStats[$cat] = [
                'total' => Guest::where('category', $cat)->count(),
                'opened' => Guest::where('category', $cat)->whereNotNull('opened_at')->count(),
                'attending' => Guest::where('category', $cat)->where('rsvp_status', 'attending')->count(),
            ];
        }

        // 2. Query Guests with Filters and Search
        $query = Guest::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('rsvp') && $request->rsvp !== 'all') {
            $query->where('rsvp_status', $request->rsvp);
        }

        if ($request->filled('view_status') && $request->view_status !== 'all') {
            if ($request->view_status === 'opened') {
                $query->whereNotNull('opened_at');
            } elseif ($request->view_status === 'unopened') {
                $query->whereNull('opened_at');
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        if ($sortBy === 'opened_desc') {
            $query->orderByRaw('opened_at IS NULL, opened_at DESC');
        } elseif ($sortBy === 'views_desc') {
            $query->orderByDesc('views_count');
        } elseif ($sortBy === 'name_asc') {
            $query->orderBy('name', 'asc');
        } else {
            $query->latest();
        }

        $guests = $query->paginate(12)->withQueryString();

        // 3. Wishes & Prayers List
        $wishes = Wish::with('guest')
            ->where('is_approved', true)
            ->orderByDesc('is_pinned')
            ->latest()
            ->paginate(8, ['*'], 'wishes_page')
            ->withQueryString();

        $totalWishes = Wish::count();

        // 4. Activity Logs
        $activities = ActivityLog::latest()->take(6)->get();

        return view('dashboard.index', compact(
            'totalGuests',
            'openedGuests',
            'unopenedGuests',
            'openedPercentage',
            'totalViews',
            'attendingGuests',
            'declinedGuests',
            'tentativeGuests',
            'pendingGuests',
            'totalPax',
            'categories',
            'categoriesStats',
            'guests',
            'wishes',
            'totalWishes',
            'activities'
        ));
    }

    public function storeGuest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'notes' => 'nullable|string|max:500',
            'pax' => 'nullable|integer|min:1|max:10',
        ]);

        $guest = Guest::createWithUniqueSlug([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'phone' => $validated['phone'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'pax' => $validated['pax'] ?? 1,
            'rsvp_status' => 'pending',
            'views_count' => 0,
        ]);

        ActivityLog::record(
            'guest_created',
            "Tamu baru ditambahkan: {$guest->name} ({$guest->category})",
            ['guest_id' => $guest->id, 'category' => $guest->category]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tamu berhasil ditambahkan!',
                'guest' => $guest,
                'whatsapp_url' => $guest->whatsapp_url,
                'personal_link' => $guest->personal_link,
                'whatsapp_message' => $guest->whatsapp_share_message,
            ]);
        }

        return redirect()->route('dashboard.index')->with('success', "Tamu '{$guest->name}' berhasil ditambahkan ke daftar undangan!");
    }

    public function updateGuest(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'rsvp_status' => 'required|in:pending,attending,declined,tentative',
            'pax' => 'required|integer|min:0|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $guest->update($validated);

        ActivityLog::record(
            'guest_updated',
            "Data tamu diperbarui: {$guest->name}",
            ['guest_id' => $guest->id]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data tamu berhasil diperbarui!',
                'guest' => $guest,
            ]);
        }

        return redirect()->route('dashboard.index')->with('success', "Data tamu '{$guest->name}' berhasil diperbarui!");
    }

    public function destroyGuest(Request $request, Guest $guest)
    {
        $name = $guest->name;
        $guest->delete();

        ActivityLog::record(
            'guest_deleted',
            "Tamu dihapus dari daftar: {$name}"
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Tamu '{$name}' telah dihapus.",
            ]);
        }

        return redirect()->route('dashboard.index')->with('success', "Tamu '{$name}' berhasil dihapus dari daftar!");
    }

    public function updateRsvp(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'rsvp_status' => 'required|in:pending,attending,declined,tentative',
            'pax' => 'nullable|integer|min:0|max:20',
        ]);

        $guest->update([
            'rsvp_status' => $validated['rsvp_status'],
            'pax' => $validated['pax'] ?? $guest->pax,
        ]);

        ActivityLog::record(
            'rsvp',
            "RSVP {$guest->name} diubah menjadi " . ucfirst($guest->rsvp_status) . " ({$guest->pax} Pax)"
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status RSVP berhasil diperbarui!',
                'guest' => $guest,
            ]);
        }

        return redirect()->back()->with('success', "Status RSVP {$guest->name} berhasil diperbarui!");
    }

    public function togglePinWish(Wish $wish)
    {
        $wish->update([
            'is_pinned' => !$wish->is_pinned,
        ]);

        return redirect()->back()->with('success', $wish->is_pinned ? 'Ucapan disematkan di paling atas!' : 'Sematkan ucapan dilepas.');
    }

    public function destroyWish(Wish $wish)
    {
        $wish->delete();
        return redirect()->back()->with('success', 'Ucapan berhasil dihapus.');
    }

    public function exportCsv()
    {
        $guests = Guest::orderBy('category')->orderBy('name')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="daftar_undangan_dian_dodhid_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($guests) {
            $output = fopen('php://output', 'w');
            // Write UTF-8 BOM for Microsoft Excel compatibility
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($output, [
                'No',
                'Nama Tamu',
                'Kategori',
                'No WhatsApp',
                'Link Undangan Personal',
                'Status Dilihat',
                'Total Dilihat (Views)',
                'Pertama Dibuka',
                'Terakhir Dibuka',
                'Konfirmasi RSVP',
                'Jumlah Pax',
                'Catatan',
            ]);

            foreach ($guests as $index => $guest) {
                fputcsv($output, [
                    $index + 1,
                    $guest->name,
                    $guest->category,
                    $guest->phone ? ("'" . $guest->phone) : '-',
                    $guest->personal_link,
                    $guest->opened_at ? 'Sudah Dibuka' : 'Belum Dibuka',
                    $guest->views_count,
                    $guest->opened_at ? $guest->opened_at->format('d/m/Y H:i:s') : '-',
                    $guest->last_opened_at ? $guest->last_opened_at->format('d/m/Y H:i:s') : '-',
                    ucfirst($guest->rsvp_status),
                    $guest->pax,
                    $guest->notes ?? '-',
                ]);
            }

            fclose($output);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function apiStats()
    {
        $totalGuests = Guest::count();
        $openedGuests = Guest::whereNotNull('opened_at')->count();
        $unopenedGuests = $totalGuests - $openedGuests;
        $totalViews = Visit::count();

        $attending = Guest::where('rsvp_status', 'attending')->count();
        $declined = Guest::where('rsvp_status', 'declined')->count();
        $tentative = Guest::where('rsvp_status', 'tentative')->count();
        $pending = Guest::where('rsvp_status', 'pending')->count();
        $totalPax = (int) Guest::where('rsvp_status', 'attending')->sum('pax');

        return response()->json([
            'total_guests' => $totalGuests,
            'opened_guests' => $openedGuests,
            'unopened_guests' => $unopenedGuests,
            'opened_percentage' => $totalGuests > 0 ? round(($openedGuests / $totalGuests) * 100, 1) : 0,
            'total_views' => $totalViews,
            'attending' => $attending,
            'declined' => $declined,
            'tentative' => $tentative,
            'pending' => $pending,
            'total_pax' => $totalPax,
        ]);
    }
}
