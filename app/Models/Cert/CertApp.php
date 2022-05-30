<?php

namespace App\Models\Cert;

use App\Models\Teacher\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertApp extends Model
{
    use HasFactory;

    protected $guarded = false;

    const REASON_COMPROMISE = 1;
    const REASON_LOST_KEYS = 2;

    public static function getReasons() {
        return [
            self::REASON_COMPROMISE => 'Произошла компрометация (факт доступа постороннего лица к ЭЦП, а также подозрение на него)',
            self::REASON_LOST_KEYS => 'Потеряны ключи электронной подписи',
        ];
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
