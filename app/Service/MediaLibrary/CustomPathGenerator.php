<?php

namespace App\Service\MediaLibrary;

use App\Models\Employee;
use App\Models\Student\Student;
use App\Models\Teacher\Teacher;
use App\Models\User;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media) : string
    {
        switch ($media->model_type) {
            case Employee::class:
                return Employee::PATH.DIRECTORY_SEPARATOR . $media->model_id.DIRECTORY_SEPARATOR . $media->id.DIRECTORY_SEPARATOR;
                break;
            case Teacher::class:
                return Teacher::PATH.DIRECTORY_SEPARATOR . $media->model_id.DIRECTORY_SEPARATOR . $media->id.DIRECTORY_SEPARATOR;
                break;
            case Student::class:
                return Student::PATH.DIRECTORY_SEPARATOR . $media->model_id.DIRECTORY_SEPARATOR . $media->id.DIRECTORY_SEPARATOR;
                break;
            case User::class:
                return User::PATH.DIRECTORY_SEPARATOR . $media->model_id.DIRECTORY_SEPARATOR . $media->id.DIRECTORY_SEPARATOR;
                break;
            default:
                return $media->id.DIRECTORY_SEPARATOR;
        }
    }

    public function getPathForConversions(Media $media) : string
    {
        return $this->getPath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive/';
    }
}