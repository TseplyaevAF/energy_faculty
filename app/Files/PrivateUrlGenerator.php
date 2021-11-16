<?php

namespace App\Files;

use App\User;
use DateTimeInterface;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Exceptions\UrlCannotBeDetermined;
use Spatie\MediaLibrary\UrlGenerator\BaseUrlGenerator;

class PrivateUrlGenerator extends BaseUrlGenerator
{

    public function getUrl(): string
    {
        $media = $this->media;
        // parent - это модель users, получаем её из отношения
        $parent = $this->media->model;

        // имя файла у нас - <id модели>.расширение
        $name = [];
        $name[] = $media->id;

        // если запрошено преобразование, его название будет в $this->conversion
        if ($this->conversion) {
            $name[] = $this->conversion->getName();
        }

        $name[] = $media->getExtensionAttribute();
        $filename = implode('.', $name);

        if (isset($parent->role->employee_id)) {
            if ($media->collection_name == "documents") {
                return "/private/employees/$parent->id/documents/$filename";
            }
            if ($media->collection_name == "archives") {
                return "/private/employees/$parent->id/archives/$filename";
            }
            if ($media->collection_name == "images") {
                return "/private/employees/$parent->id/images/$filename";
            }
        }

    }

    public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
    {
        throw UrlCannotBeDetermined::filesystemDoesNotSupportTemporaryUrls();
    }

    public function getResponsiveImagesDirectoryUrl(): string
    {
        throw new \Exception("No responsive directory url for private files.");
    }

    public function getPath(): string {
        return Storage::disk($this->media->disk)->path('') . $this->getPathRelativeToRoot();
    }

}