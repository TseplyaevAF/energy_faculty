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
            'lesson_id' => 'required|integer|exists:lessons,id',
            'class_type_id' => 'required|string',
            'classroom_id' => 'required|string',
            'group_id' => 'required|integer|exists:groups,id',
        ];
    }

    public function messages()
    {
        return [
            'class_type_id.required' => 'Необходимо заполнить тип занятия',
            'classroom_id.required' => 'Необходимо заполнить номер кабинета',
        ];
    }
}
