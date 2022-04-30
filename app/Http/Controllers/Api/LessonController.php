<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\LessonFilter;
use App\Http\Requests\Admin\Lesson\FilterRequest;
use App\Models\Lesson;

class LessonController extends Controller
{
    private function lessonFilter($data) {
        $filter = app()->make(LessonFilter::class, ['queryParams' => array_filter($data)]);
        return Lesson::filter($filter)->get();
    }

    public function getSemesters(FilterRequest $request) {
        $lessons = self::lessonFilter($request->validated())->unique('semester');
        $semesters = [];
        foreach ($lessons as $lesson) {
            $semesters[] = $lesson->semester;
        }
        return $semesters;
    }

    public function getDisciplines(FilterRequest $request) {
        $lessons = self::lessonFilter($request->validated())->unique('discipline_id');;
        $disciplines = [];
        foreach ($lessons as $lesson) {
            $disciplines[] = $lesson->discipline;
        }
        return $disciplines;
    }

    public function getGroups(FilterRequest $request) {
        $lessons = self::lessonFilter($request->validated())->unique('group_id');
        $groups = [];
        foreach ($lessons as $lesson) {
            $groups[] = $lesson->group;
        }
        return $groups;
    }

    public function getYears(FilterRequest $request) {
        $lessons = self::lessonFilter($request->validated())->unique('year_id');
        $years = [];
        foreach ($lessons as $lesson) {
            $years[] = $lesson->year;
        }
        return $years;
    }
}
