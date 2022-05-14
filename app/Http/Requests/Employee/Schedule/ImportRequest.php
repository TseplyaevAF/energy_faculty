<?php

namespace App\Http\Requests\Employee\Schedule;

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
            'excel_file' => 'required|mimes:xlsx|max:10240',
            'semester' => 'required|integer|between:1,8',
            'group_id' => 'required|integer|exists:groups,id',
        ];
    }

    public function messages()
    {
        return [
            'excel_file.mimes' => 'Файл должен иметь следующий формат: xlsx',
            'excel_file.max' => 'Файл не должен превышать 10МБ',
            'excel_file.required' => 'Необходимо выбрать файл',
        ];
    }
}
