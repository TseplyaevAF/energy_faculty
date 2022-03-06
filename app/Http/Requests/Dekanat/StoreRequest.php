<?php

namespace App\Http\Requests\Dekanat;

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
            'form_exam' => 'required|integer',
            'lesson_id' => 'required|integer|exists:lessons,id',
            'exam_date' => 'nullable|string',
            'finish_date' => 'nullable|string',
            'private_key' => 'required|file'
        ];
    }
}
