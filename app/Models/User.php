<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Student\Student;
use App\Models\Teacher\Teacher;
use App\Models\Traits\Filterable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,
    SoftDeletes, Filterable, HasMediaTrait;

    const ROLE_ADMIN = 1;
    const ROLE_STUDENT = 2;
    const ROLE_TEACHER = 3;
    const ROLE_EMPLOYEE = 4;
    const ROLE_CA = 5;
    const ROLE_DEKANAT = 6;

    const PATH = 'users';

    public function registerMediaCollections(): void
    {
    $this->addMediaCollection('avatar')->singleFile();
    }

    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Администратор',
            self::ROLE_STUDENT => 'Студент',
            self::ROLE_TEACHER => 'Преподаватель',
            self::ROLE_EMPLOYEE => 'Сотрудник кафедры',
            self::ROLE_CA => 'Сотрудник УЦ',
            self::ROLE_DEKANAT => 'Сотрудник деканата'
        ];
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function student() {
        return $this->hasOne(Student::class);
    }

    public function teacher() {
        return $this->hasOne(Teacher::class);
    }

    public function employee() {
        return $this->hasOne(Employee::class);
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
