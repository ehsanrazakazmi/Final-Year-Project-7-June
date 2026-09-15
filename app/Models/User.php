<?php

namespace App\Models;

use App\Models\Message;
use App\Models\Services;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Legacy integer role values stored on the `role` column.
     *
     * Spatie is the authority for authorisation; this column is kept in sync
     * because the API returns raw `users` rows and several views still read it.
     */
    public const ROLE_ADMIN = 1;
    public const ROLE_RESIDENT = 2;
    public const ROLE_TECHNICIAN = 3;

    /**
     * Legacy integer role => Spatie role name.
     *
     * @var array<int, string>
     */
    public const ROLE_MAP = [
        self::ROLE_ADMIN => 'admin',
        self::ROLE_RESIDENT => 'resident',
        self::ROLE_TECHNICIAN => 'technician',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'integer',
    ];

    /**
     * Spatie role name for a legacy integer, or null if unrecognised.
     */
    public static function roleNameFor(?int $role): ?string
    {
        return self::ROLE_MAP[$role] ?? null;
    }

    /**
     * Legacy integer for a Spatie role name, or null if unrecognised.
     */
    public static function roleValueFor(?string $name): ?int
    {
        $flipped = array_flip(self::ROLE_MAP);

        return $flipped[$name] ?? null;
    }

    /**
     * Assign a Spatie role and keep the legacy `role` column in step.
     */
    public function assignRoleByName(string $name): void
    {
        $this->syncRoles([$name]);

        $value = self::roleValueFor($name);

        if ($value !== null && $this->role !== $value) {
            $this->forceFill(['role' => $value])->save();
        }
    }

    /**
     * The user's Spatie role name, falling back to the legacy column.
     */
    public function roleName(): ?string
    {
        return $this->getRoleNames()->first() ?? self::roleNameFor($this->role);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlist()
    {
        return $this->belongsToMany(Services::class, 'wishlists');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
