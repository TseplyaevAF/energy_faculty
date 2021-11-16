<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\Traits\Filterable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, 
    SoftDeletes, Filterable, HasMediaTrait;

    const ROLE_ADMIN = -1;
    const ROLE_STUDENT = 1;
    const ROLE_TEACHER = 2;
    const ROLE_EMPLOYEE = 3;

    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Администратор',
            self::ROLE_STUDENT => 'Студент',
            self::ROLE_TEACHER => 'Преподаватель',
            self::ROLE_EMPLOYEE => 'Сотрудник',
        ];
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'phone_number',
        'avatar',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
