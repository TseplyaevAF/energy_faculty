<?php

namespace App\Files;

use DateTimeInterface;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Exceptions\UrlCannotBeDetermined;
use Spatie\MediaLibrary\UrlGenerator\BaseUrlGenerator;

class PrivateUrlGenerator extends BaseUrlGenerator
{

    public function getUrl(): string
    {
        $media = $this->media;
        $name = [];
        $name[] = $media->name;

        // если запрошено преобразование, его название будет в $this->conversion
        if ($this->conversion) {
            $name[] = $this->conversion->getName();
        }

        $name[] = $media->getExtensionAttribute();
        $filename = implode('.', $name);

        return sprintf('%s/%s/%s/%s', $media->model_id, $media->collection_name, $media->id, $filename);

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
