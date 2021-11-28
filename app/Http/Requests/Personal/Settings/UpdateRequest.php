<?php

namespace App\Http\Requests\Personal\Settings;

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
        // dd($this->all());
        return [
            'avatar' => 'nullable|image',
            'surname' => 'nullable|string',
            'name' => 'nullable|string',
            'patronymic' => 'nullable|string',
            'phone_number' => 'nullable|regex:%^\\+\\d[-]\\(\\d{3}\\)[-]\\d{3}[-]\\d{2}[-]\\d{2}$%',
            'email' => ['required', 'string', 'email', 'unique:users,email,' . $this->user_id, 'regex:/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/'],
        ];
    }
}
