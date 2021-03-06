<?php

namespace App\Http\Requests\News;

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
            'content' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'is_slider_item' => 'nullable|boolean',
            'tag_id' => 'nullable|integer|exists:tags,id',
            'chair_id' => 'nullable|integer|exists:chairs,id',
            'date' => 'nullable|array',
            'date.*' => 'nullable|string',
            'per_page' => 'nullable|integer',
        ];
    }
}
