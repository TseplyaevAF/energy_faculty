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
            'surname' => 'required|string',
            'name' => 'required|string',
            'patronymic' => 'nullable|string',
            'avatar' => 'nullable|string',

            'phone_number' => 'nullable|regex:%^\\+\\d[-]\\(\\d{3}\\)[-]\\d{3}[-]\\d{2}[-]\\d{2}$%',
            'email' => ['required', 'string', 'email', 'unique:users,email,' . $this->user_id, 'regex:/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/'],
            'user_id' => 'required|numeric|exists:users,id',
            'role_id' => 'required|numeric',
            
            'post' => 'required|string',
            'activity' => 'nullable',
            'work_experience' => 'required|numeric',
            'address' => 'nullable',
            'chair_id' => 'required|integer|exists:chairs,id',
            'disciplines_ids' => 'nullable|array',
            'disciplines_ids.*' => 'integer|exists:disciplines,id',
        ];
    }
}
