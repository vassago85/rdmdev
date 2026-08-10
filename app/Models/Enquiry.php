<?php

namespace App\Models;

use App\Enums\EnquiryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $attributes = [
        'status' => 'new',
    ];

    protected $casts = [
        'read_at'      => 'datetime',
        'follow_up_at' => 'date',
        'status'       => EnquiryStatus::class,
        'photos'       => 'array',
    ];

    /** Public URLs for any attached photos. */
    public function photoUrls(): array
    {
        return collect($this->photos ?? [])
            ->map(fn (string $path) => asset('storage/' . $path))
            ->all();
    }

    public function markRead(): void
    {
        if (! $this->read_at) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /** Open leads still needing attention (new / contacted / quoted). */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', EnquiryStatus::openValues());
    }

    /** WhatsApp deep-link to this enquiry's phone number. */
    public function whatsappUrl(?string $text = null): ?string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $this->phone);

        if ($digits === '') {
            return null;
        }

        // Normalise SA local numbers (0XXXXXXXXX) to international (27XXXXXXXXX).
        if (str_starts_with($digits, '0')) {
            $digits = '27' . substr($digits, 1);
        }

        $url = 'https://wa.me/' . $digits;

        return $text ? $url . '?text=' . rawurlencode($text) : $url;
    }
}
