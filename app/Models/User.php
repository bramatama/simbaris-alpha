<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $user_id 
 * @property string $public_id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $contact_info
 * @property string|null $profile_picture_path
 * @property string $password
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    /**
     * Get the route key for implicit model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'user_id';
    }

    protected $fillable = [
        'public_id',
        'name',
        'email',
        'role',
        'contact_info',
        'profile_picture_path',
        'password',
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

    /**
     * Get the admin profile for this user.
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class, 'user_id', 'user_id');
    }

    /**
     * Get the official team profile for this user.
     */
    public function officialTeam(): HasOne
    {
        return $this->hasOne(OfficialTeam::class, 'user_id', 'user_id');
    }

    /**
     * Get the judge profile for this user.
     */
    public function judge(): HasOne
    {
        return $this->hasOne(Judge::class, 'user_id', 'user_id');
    }

    /**
     * Get the committee profile for this user.
     */
    public function committee(): HasOne
    {
        return $this->hasOne(Committee::class, 'user_id', 'user_id');
    }
}
