<?php

namespace App\Http\Requests\Admin\User\Student;

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
            'student_id_number' => ['required', 'numeric', 'unique:students,student_id_number,' . $this->student_id],
            'group_id' => 'required|exists:groups,id',
        ];
    }
}
