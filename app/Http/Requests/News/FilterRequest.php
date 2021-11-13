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
        // $this->merge([
        //     'date' => [
        //         'date_start' => $this->date_start,
        //         'date_end' => $this->date_end,
        //     ]
        // ]);
        // dd($this->request);

        return [
            'content' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'date' => 'nullable|array',
            'date.*' => 'nullable|string',
        ];
    }
}
