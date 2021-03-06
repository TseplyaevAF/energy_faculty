<?php

namespace App\Http\Requests\Personal\Task;

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
            'task' => 'required|mimes:pdf,docx|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'task.mimes' => 'Файл должен иметь один из следующих фоматов: pdf,docx',
            'task.max' => 'Файл не должен превышать 10МБ',
        ];
    }
}
