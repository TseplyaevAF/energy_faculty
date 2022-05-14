<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTime extends Model
{
    use HasFactory;

    public function getClassTime() {
        return $this->start_time . '-' . $this->end_time;
    }
}
