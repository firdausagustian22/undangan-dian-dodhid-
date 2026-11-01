<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guest;
use App\Models\Wish;
use App\Models\Rsvp;
use App\Models\Visit;
use App\Models\ActivityLog;
use Carbon\Carbon;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        $guests = [
            [
                'name' => 'Bapak Joko Widodo & Ibu',
                'category' => 'VIP',
                'phone' => '081234567890',
                'views_count' => 5,
                'opened_at' => Carbon::now()->subDays(2)->addHours(3),
                'last_opened_at' => Carbon::now()->subHours(2),
                'rsvp_status' => 'attending',
                'pax' => 2,
                'notes' => 'Tamu kehormatan',
            ],
            [
                'name' => 'Dr. H. Bambang Soeprapto',
                'category' => 'Keluarga',
                'phone' => '081398765432',
                'views_count' => 3,
                'opened_at' => Carbon::now()->subDays(1)->addHours(5),
                'last_opened_at' => Carbon::now()->subHours(6),
                'rsvp_status' => 'attending',
                'pax' => 4,
                'notes' => 'Keluarga mempelai pria',
            ],
            [
                'name' => 'Budi Santoso, S.Kom',
                'category' => 'Teman',
                'phone' => '085712345678',
                'views_count' => 2,
                'opened_at' => Carbon::now()->subHours(10),
                'last_opened_at' => Carbon::now()->subHours(1),
                'rsvp_status' => 'attending',
                'pax' => 2,
                'notes' => 'Teman SMA Dian',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'category' => 'Teman',
                'phone' => '089611223344',
                'views_count' => 1,
                'opened_at' => Carbon::now()->subHours(4),
                'last_opened_at' => Carbon::now()->subHours(4),
                'rsvp_status' => 'tentative',
                'pax' => 1,
                'notes' => 'Masih menyesuaikan jadwal dinas luar kota',
            ],
            [
                'name' => 'Ahmad Fauzi & Rekan',
                'category' => 'Rekan Kerja',
                'phone' => '081299887766',
                'views_count' => 0,
                'opened_at' => null,
                'last_opened_at' => null,
                'rsvp_status' => 'pending',
                'pax' => 1,
                'notes' => 'Divisi IT Development',
            ],
            [
                'name' => 'Rina Wijaya, M.Sc',
                'category' => 'Teman',
                'phone' => '087855443322',
                'views_count' => 2,
                'opened_at' => Carbon::now()->subHours(18),
                'last_opened_at' => Carbon::now()->subHours(8),
                'rsvp_status' => 'declined',
                'pax' => 0,
                'notes' => 'Berhalangan karena acara keluarga di Surabaya',
            ],
            [
                'name' => 'Ir. Hendra Gunawan',
                'category' => 'VIP',
                'phone' => '081122334455',
                'views_count' => 4,
                'opened_at' => Carbon::now()->subDays(3),
                'last_opened_at' => Carbon::now()->subHours(12),
                'rsvp_status' => 'attending',
                'pax' => 2,
                'notes' => 'Mitra Bisnis Utama',
            ],
            [
                'name' => 'Tante Lisa & Om Dedi',
                'category' => 'Keluarga',
                'phone' => '081566778899',
                'views_count' => 1,
                'opened_at' => Carbon::now()->subHours(14),
                'last_opened_at' => Carbon::now()->subHours(14),
                'rsvp_status' => 'attending',
                'pax' => 3,
                'notes' => 'Keluarga mempelai wanita',
            ],
            [
                'name' => 'Dimas Prasetyo',
                'category' => 'Teman',
                'phone' => '082133445566',
                'views_count' => 0,
                'opened_at' => null,
                'last_opened_at' => null,
                'rsvp_status' => 'pending',
                'pax' => 1,
                'notes' => 'Sahabat Kuliah Dodhid',
            ],
            [
                'name' => 'Anisa Rahmawati',
                'category' => 'Rekan Kerja',
                'phone' => '085233114477',
                'views_count' => 1,
                'opened_at' => Carbon::now()->subHours(7),
                'last_opened_at' => Carbon::now()->subHours(7),
                'rsvp_status' => 'pending',
                'pax' => 1,
                'notes' => 'Sudah buka link, belum isi RSVP',
            ],
        ];

        foreach ($guests as $guestData) {
            $guest = Guest::createWithUniqueSlug($guestData);

            // Create Visit records if views_count > 0
            if ($guest->views_count > 0) {
                for ($i = 0; $i < $guest->views_count; $i++) {
                    Visit::create([
                        'guest_id' => $guest->id,
                        'guest_name' => $guest->name,
                        'ip_address' => '127.0.0.' . rand(1, 99),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'referer' => 'https://api.whatsapp.com/',
                        'created_at' => $guest->opened_at ? $guest->opened_at->copy()->addMinutes($i * 35) : Carbon::now(),
                    ]);
                }
            }

            // Create RSVP record if responded
            if ($guest->rsvp_status !== 'pending') {
                Rsvp::create([
                    'guest_id' => $guest->id,
                    'name' => $guest->name,
                    'phone' => $guest->phone,
                    'status' => $guest->rsvp_status,
                    'pax' => $guest->pax,
                    'notes' => $guest->notes,
                    'ip_address' => '127.0.0.1',
                ]);
            }
        }

        // Create sample Wishes
        $wishes = [
            [
                'guest_id' => 1,
                'name' => 'Bapak Joko Widodo & Ibu',
                'relationship' => 'VIP',
                'message' => 'Selamat menempuh hidup baru untuk Dian & Dodhid. Semoga menjadi keluarga yang sakinah, mawaddah, warahmah, serta selalu dilimpahi keberkahan dan kebahagiaan.',
                'attendance' => 'attending',
                'is_pinned' => true,
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'guest_id' => 3,
                'name' => 'Budi Santoso, S.Kom',
                'relationship' => 'Teman SMA Dian',
                'message' => 'Happy wedding Dian & Dodhid! Akhirnya berlabuh di pelaminan juga yaa. Doa terbaik buat kalian berdua, semoga langgeng sampai kakek nenek! Sampai ketemu di resepsi!',
                'attendance' => 'attending',
                'is_pinned' => false,
                'created_at' => Carbon::now()->subHours(10),
            ],
            [
                'guest_id' => 6,
                'name' => 'Rina Wijaya, M.Sc',
                'relationship' => 'Sahabat Dian',
                'message' => 'Dian sayang, maaf banget belum bisa hadir langsung karena masih ada tugas di Surabaya. Tapi doa terbaik selalu menyertai pernikahan kalian berdua ya. Barakallahu lakuma!',
                'attendance' => 'declined',
                'is_pinned' => false,
                'created_at' => Carbon::now()->subHours(8),
            ],
            [
                'guest_id' => 2,
                'name' => 'Dr. H. Bambang Soeprapto',
                'relationship' => 'Paman Mempelai',
                'message' => 'Alhamdulillah, selamat untuk ananda Dian dan Dodhid. Semoga senantiasa rukun, saling melengkapi, dan segera dikaruniai keturunan yang sholeh dan sholehah.',
                'attendance' => 'attending',
                'is_pinned' => true,
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'guest_id' => 4,
                'name' => 'Siti Nurhaliza',
                'relationship' => 'Teman Kampus',
                'message' => 'Masya Allah selamat ya Dian dan Dodhid! Semoga dilancarkan acaranya sampai hari H tanpa halangan apapun. Aamiin ya rabbal alamin.',
                'attendance' => 'tentative',
                'is_pinned' => false,
                'created_at' => Carbon::now()->subHours(4),
            ],
        ];

        foreach ($wishes as $wish) {
            Wish::create($wish);
        }

        // Sample Activity Logs
        ActivityLog::record('rsvp', 'Bapak Joko Widodo mengonfirmasi Hadir (2 Pax)', ['pax' => 2]);
        ActivityLog::record('rsvp', 'Budi Santoso mengonfirmasi Hadir (2 Pax)', ['pax' => 2]);
        ActivityLog::record('wish', 'Dr. H. Bambang Soeprapto mengirim doa restu');
        ActivityLog::record('view', 'Siti Nurhaliza membuka undangan digital');
        ActivityLog::record('view', 'Budi Santoso membuka undangan digital');
    }
}
