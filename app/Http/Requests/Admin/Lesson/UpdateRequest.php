<?php

namespace App\Http\Requests\Admin\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'semester' => 'required|integer',
            'teacher_id' => 'integer|exists:teachers,id',
            'disciplines_ids' => 'required|array',
            'disciplines_ids.*' => 'integer|exists:disciplines,id',
            'group_id' => 'integer|exists:groups,id'
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
