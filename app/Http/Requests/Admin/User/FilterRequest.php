<?php

namespace App\Http\Requests\Admin\User;

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
        // dd($this->request->all());
        // $files = app('request')->get('user_id');
        return [
            'role_id' => 'nullable|integer',
            'user_id' => 'nullable|integer|exists:users,id',
            'full_name' => 'nullable|string',
        ];
    }
}
