<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'week_type' => 'nullable|integer',
            'day' => 'nullable|integer',
            'teacher' => 'nullable|string'
//            'class_time_id' => 'required|integer',
//            'lesson_id' => 'required|integer|exists:lessons,id',
//            'class_type_id' => 'required|integer|exists:class_types,id',
//            'classroom_id' => 'required|integer|exists:classrooms,id'
        ];
    }
}
