<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use App\Observers\ContactObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

#[ObservedBy(ContactObserver::class)]
class Contact extends Model
{
    use HasFactory, Searchable;

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
        'country',
        'zip_code',
        'photo',
    ];

    protected $appends = [
        'photoUrl',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new UserScope);
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'email' => $this->email,
            'phone_number' => (int) $this->phone_number,
            'cellphone_number' => (int) $this->cellphone_number,
            'address' => $this->address,
            'district' => $this->district,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'zip_code' => $this->zip_code,
        ];
    }

    public function searchableAs(): string
    {
        return 'contacts_index';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        return Storage::disk('public')->url($this->photo);
    }
}
