<?php

namespace App\Http\Requests\Admin\User\Teacher;

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
            'post' => 'required|string',
            'activity' => 'nullable|string',
            'work_experience' => 'nullable|integer',
            'address' => 'nullable|string',
            'chair_id' => 'required|integer|exists:chairs,id',
            'disciplines_ids' => 'nullable|array',
            'disciplines_ids.*' => 'integer|exists:disciplines,id',
        ];
    }
}
