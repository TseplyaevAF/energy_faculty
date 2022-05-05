<?php

namespace App\Http\Requests\Personal\Mark;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParentsContactsRequest extends FormRequest
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
            'parents' => 'required|array',
            'parents.*' => 'required|array',
            'parents.*.FIO' => 'nullable|string',
            'parents.*.phone' => 'nullable|regex:%^\\+\\d[-]\\(\\d{3}\\)[-]\\d{3}[-]\\d{2}[-]\\d{2}$%',
            'parents.*.email' => 'nullable|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
        ];
    }

    public function messages()
    {
        return [
            'parents.*.phone.regex' => 'Некорректно введён номер',
            'parents.*.email.regex' => 'Некорректно введён email',
        ];
    }
}
