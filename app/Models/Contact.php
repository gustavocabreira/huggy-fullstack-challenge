<?php

namespace App\Models;

use App\Observers\ContactObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(ContactObserver::class)]
class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date_of_birth',
        'email',
        'phone_number',
        'cellphone_number',
        'address',
        'district',
        'city',
        'state',
        'zip_code',
        'photo',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
