<?php

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    public const PICKUP_ANTAR_SENDIRI = 'antar_sendiri';

    public const PICKUP_PICKUP = 'pickup';

    public const PICKUP_TYPES = [
        self::PICKUP_ANTAR_SENDIRI,
        self::PICKUP_PICKUP,
    ];

    public const STATUS_BOOKING_MASUK = 'booking_masuk';

    public const STATUS_DITERIMA = 'diterima';

    public const STATUS_DICUCI = 'dicuci';

    public const STATUS_DIKERINGKAN = 'dikeringkan';

    public const STATUS_DISETRIKA = 'disetrika';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_DIAMBIL = 'diambil';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const STATUSES = [
        self::STATUS_BOOKING_MASUK,
        self::STATUS_DITERIMA,
        self::STATUS_DICUCI,
        self::STATUS_DIKERINGKAN,
        self::STATUS_DISETRIKA,
        self::STATUS_SELESAI,
        self::STATUS_DIAMBIL,
        self::STATUS_DIBATALKAN,
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'booking_code',
        'user_id',
        'customer_id',
        'service_id',
        'booking_date',
        'estimated_finish_date',
        'weight',
        'total_price',
        'pickup_type',
        'status',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'estimated_finish_date' => 'date',
            'weight' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
