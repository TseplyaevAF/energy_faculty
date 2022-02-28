<?php

namespace App\Models;

use App\Models\Teacher\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertApp extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
