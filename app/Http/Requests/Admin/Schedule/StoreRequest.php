<?php

namespace App\Http\Requests\Admin\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'week_type' => 'required|integer',
            'day' => 'required|integer',
            'class_time_id' => 'required|integer',
            'discipline_id' => 'required|integer|exists:disciplines,id',
            'teacher_id' => 'required|integer|exists:teachers,id',
            'group_id' => 'required|integer|exists:groups,id',
            'class_type_id' => 'required|integer',
            'classroom_id' => 'required|integer',
        ];
    }
}
