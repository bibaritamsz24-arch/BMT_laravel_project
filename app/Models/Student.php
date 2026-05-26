<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_account_id',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'contact',
        'email',
        'degree_id',
    ];

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ], fn (?string $name): bool => filled($name))));
    }

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class)
            ->withDefault([
                'title' => 'No degree assigned',
            ]);
    }

    public function userAccount(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class)
            ->withDefault([
                'username' => 'No linked account',
                'email' => null,
            ]);
    }
}
