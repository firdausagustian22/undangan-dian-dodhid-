<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'phone',
        'views_count',
        'opened_at',
        'last_opened_at',
        'rsvp_status',
        'pax',
        'notes',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'last_opened_at' => 'datetime',
        'views_count' => 'integer',
        'pax' => 'integer',
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }

    public function wishes()
    {
        return $this->hasMany(Wish::class);
    }

    public static function createWithUniqueSlug(array $attributes): self
    {
        $baseSlug = Str::slug($attributes['name'] ?? 'tamu');
        if (empty($baseSlug)) {
            $baseSlug = 'tamu';
        }

        $slug = $baseSlug;
        $count = 1;
        while (static::where('slug', $slug)->exists()) {
            $count++;
            $slug = "{$baseSlug}-{$count}";
        }

        $attributes['slug'] = $slug;
        return static::create($attributes);
    }

    public function getPersonalLinkAttribute(): string
    {
        return url('/to/' . $this->slug);
    }

    public function getCleanPhoneAttribute(): ?string
    {
        if (empty($this->phone)) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    public function getWhatsAppShareMessageAttribute(): string
    {
        $link = $this->personal_link;
        return "Kepada Yth. *{$this->name}*,\n\n" .
               "Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk hadir di momen istimewa dan bahagia pernikahan kami:\n\n" .
               "💍 *Dian & Dodhid*\n\n" .
               "Buka undangan digital Anda melalui tautan resmi berikut:\n" .
               "👉 {$link}\n\n" .
               "Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir serta memberikan doa restu kepada kami berdua.\n\n" .
               "Terima kasih hangat,\n" .
               "*Dian & Dodhid*";
    }

    public function getWhatsAppUrlAttribute(): string
    {
        $phone = $this->clean_phone;
        $message = urlencode($this->whatsapp_share_message);

        if ($phone) {
            return "https://api.whatsapp.com/send?phone={$phone}&text={$message}";
        }

        return "https://api.whatsapp.com/send?text={$message}";
    }
}
