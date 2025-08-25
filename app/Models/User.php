<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'provider',
        'socialite_verified_at',
        'provider_id',
        'phone',
        'address',
        'avatar',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected const SORT_OPTIONS = [
        'a_to_z' => ['name', 'asc'],
        'z_to_a' => ['name', 'desc']
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['search'] ?? null,
            fn($q, $search) =>
            $q->where(
                fn($q) =>
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
            )
        );

        $query->when(
            $filters['role'] ?? null,
            fn($q) =>
            $q->where('role', $filters['role'])
        );

        $query->when($filters['sort'] ?? null, function ($q) use ($filters) {
            if (array_key_exists($filters['sort'], self::SORT_OPTIONS)) {
                [$column, $direction] = self::SORT_OPTIONS[$filters['sort']];
                $q->orderBy($column, $direction);
            } else {
                $q->orderBy('created_at', 'desc');
            }
        }, fn($q) => $q->orderBy('created_at', 'desc'));

        return $query;
    }
}
