<?php

namespace App\Http\Requests\Personal\StudentProgress;

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
            'lesson_id' => 'integer|exists:lessons,id',
            'student_progress' => 'required|mimes:xlsx|max:10240',
            'monthNumber' => 'required|integer|between:1,12',
        ];
    }

    public function messages()
    {
        return [
            'student_progress.mimes' => 'Файл должен иметь один из следующих фоматов: xlsx',
            'student_progress.max' => 'Файл не должен превышать 10МБ',
        ];
    }
}
