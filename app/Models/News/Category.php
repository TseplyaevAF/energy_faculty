<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = false;

    const NEWS = 1;
    const EVENTS = 2;
    const CONFERENCES = 3;
    const OLYMPICS = 4;

    public static function getCategories()
    {
        return [
            self::NEWS => 'Новости',
            self::EVENTS => 'Мероприятия',
            self::CONFERENCES => 'Конференции',
            self::OLYMPICS => 'Олимпиады'
        ];
    }
}
