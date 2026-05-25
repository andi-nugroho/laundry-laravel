<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    public const GENDER_MALE = 'male';

    public const GENDER_FEMALE = 'female';

    public const GENDERS = [
        self::GENDER_MALE,
        self::GENDER_FEMALE,
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'gender',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
