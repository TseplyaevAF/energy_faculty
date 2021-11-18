<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Employee extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use HasMediaTrait;

    protected $guarded = false;

    const DOCUMENTS = 0;
    const IMAGES = 1;
    const ARCHIVES = 2;
    const PATH ='employees';

    public function registerAllMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(180)
            ->height(180);
    }

    public static function getFilesCategories()
    {
        return [
            self::DOCUMENTS => 'Документы (*.doc, *.txt, *.xls)',
            self::IMAGES => 'Изображения (*.jpg, *.jpeg, *.png)',
            self::ARCHIVES => 'Архивы (*.rar, *.zip)',
        ];
    }

    public function role(){
        return $this->hasOne(Role::class);
    }

    public function chair() {
        return $this->belongsTo(Chair::class);
    }
}
