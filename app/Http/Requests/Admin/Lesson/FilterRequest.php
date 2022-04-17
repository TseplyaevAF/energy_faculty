<?php

namespace App\Http\Requests\Admin\Lesson;

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
            'group_id' => 'nullable|integer|exists:groups,id',
            'semester' => 'nullable|integer',
            'year_id' => 'nullable|integer|exists:years,id',
            'discipline_id' => 'nullable|integer|exists:disciplines,id',
            'teacher_id' => 'nullable|integer|exists:teachers,id'
        ];
    }
}
