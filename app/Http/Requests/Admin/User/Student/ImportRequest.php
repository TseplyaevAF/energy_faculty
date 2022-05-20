<?php

namespace App\Http\Requests\Admin\User\Student;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
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
            'excel_file' => 'required|mimes:xlsx',
            'title' => 'required|string|max:20',
            'chair_id' => 'required|integer|exists:chairs,id',
        ];
    }

    public function messages()
    {
        return [
            'excel_file.mimes' => 'Файл должен иметь следующий формат: xlsx',
            'excel_file.required' => 'Необходимо выбрать файл',
        ];
    }
}
