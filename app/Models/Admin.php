<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Admin
 *
 * @property int $admin_id
 * @property string $admin_name
 * @property string $admin_surname
 * @property string $admin_email
 * @property string $admin_login
 * @property string $admin_password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAdminSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'admin_id';
    protected $table = 'table_admin';

    protected $guard = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_name', 'admin_surname', 'admin_email','admin_password','admin_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'admin_password', 'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->admin_password;
    }

}
