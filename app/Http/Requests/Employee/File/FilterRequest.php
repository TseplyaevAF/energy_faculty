<?php

namespace App\Http\Requests\Employee\File;

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
            'title' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'date' => 'nullable|array',
            'date.*' => 'nullable|string',
        ];
    }
}
