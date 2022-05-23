<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\ScheduleFilter;
use App\Http\Requests\Schedule\FilterRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule\Schedule;

class ScheduleController extends Controller
{

    public function index(FilterRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(ScheduleFilter::class, ['queryParams' => array_filter($data)]);
        return ScheduleResource::collection(Schedule::filter($filter)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->filter(function ($value) {
                return isset($value->lesson->teacher);
            })
        );
    }
}
