<?php

namespace App\Models;

use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    public const METHOD_CASH = 'cash';

    public const METHOD_TRANSFER = 'transfer';

    public const METHOD_EWALLET = 'ewallet';

    public const METHODS = [
        self::METHOD_CASH,
        self::METHOD_TRANSFER,
        self::METHOD_EWALLET,
    ];

    public const STATUS_UNPAID = 'unpaid';

    public const STATUS_PARTIAL = 'partial';

    public const STATUS_PAID = 'paid';

    public const STATUSES = [
        self::STATUS_UNPAID,
        self::STATUS_PARTIAL,
        self::STATUS_PAID,
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'booking_id',
        'payment_code',
        'payment_date',
        'payment_method',
        'amount_paid',
        'total_bill',
        'change_amount',
        'payment_status',
        'notes',
        'processed_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payment_date' => 'datetime',
            'amount_paid' => 'decimal:2',
            'total_bill' => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    public static function statusForAmount(float|int|string $amountPaid, float|int|string $totalBill): string
    {
        if ((float) $amountPaid === 0.0) {
            return self::STATUS_UNPAID;
        }

        if ((float) $amountPaid < (float) $totalBill) {
            return self::STATUS_PARTIAL;
        }

        return self::STATUS_PAID;
    }

    public static function generatePaymentCode(string $paymentDate): string
    {
        $year = \Illuminate\Support\Carbon::parse($paymentDate)->format('Y');
        $lastCode = self::query()
            ->where('payment_code', 'like', "PAY-{$year}-%")
            ->orderByDesc('payment_code')
            ->value('payment_code');

        $nextNumber = $lastCode ? ((int) substr($lastCode, -4)) + 1 : 1;

        return sprintf('PAY-%s-%04d', $year, $nextNumber);
    }

    public function isCod(): bool
    {
        $notes = strtolower((string) $this->notes);

        return str_contains($notes, 'payment_channel=cod')
            || str_contains($notes, 'cod / bayar di tempat');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
