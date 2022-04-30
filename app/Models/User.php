<?php

namespace App\Models;

use App\Models\Group\GroupNews;
use App\Notifications\SendVerifyWithQueueNotification;
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

class User extends Authenticatable implements HasMedia
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

    public function fullName() {
        return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
    }

    public function surnameName() {
        return $this->surname . ' ' . $this->name;
    }

    public function unread_posts() {
        return $this->belongsToMany(GroupNews::class, 'unread_posts', 'user_id', 'group_news_id');
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
        'is_active_2fa'
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

    /**
     * generate OTP and send sms
     *
     */
    public function generateCode()
    {
        $ch = curl_init("https://sms.ru/code/call");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "phone" => auth()->user()->phone_number, // номер телефона пользователя
            "ip" => $_SERVER["REMOTE_ADDR"], // ip адрес пользователя
            "api_id" => getenv("API_ID")
        )));
        $body = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($body);
        if ($json) { // Получен ответ от сервера
            if ($json->status == "OK") { // Запрос выполнился
                UserCode::updateOrCreate([
                    'user_id' => auth()->user()->id,
                    'code' => $json->code
                ]);
            } else { // Ошибка в запросе
                throw new \Exception("Звонок не может быть выполнен: " . $json->status_text);
            }

        } else {
            throw new \Exception("Запрос не выполнился. Не удалось установить связь с сервером.");
        }
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new SendVerifyWithQueueNotification());
    }
}
