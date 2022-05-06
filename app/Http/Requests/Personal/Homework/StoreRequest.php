<?php

namespace App\Http\Requests\Personal\Homework;

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
            'homework' => 'required|mimes:pdf,docx',
            'task_id' => 'integer|exists:tasks,id',
        ];
    }

    public function messages()
    {
        return [
            'homework.mimes' => 'Файл должен иметь один из следующих фоматов: pdf,docx'
        ];
    }
}
