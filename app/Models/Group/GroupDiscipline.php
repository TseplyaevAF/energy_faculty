<?php

namespace App\Models\Group;

use App\Models\Discipline;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupDiscipline extends Model
{
    use HasFactory;

    public function discipline() {
        return $this->belongsTo(Discipline::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }
}
