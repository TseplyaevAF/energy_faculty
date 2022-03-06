<?php

namespace App\Models\Cert;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Certificate extends Model implements HasMedia
{
    use HasFactory, HasMediaTrait;

    CONST PATH = 'certs';

    protected $guarded = false;
}
