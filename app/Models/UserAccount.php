<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class UserAccount extends Authenticatable
{
    protected $attributes = [
        'must_change_password' => true,
    ];

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    /**
     * Hash user account passwords with the application's configured hasher.
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::isHashed($value)
            ? $value
            : Hash::make($value);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->student?->full_name ?: $this->username;
    }
}
