<?php

namespace App\Http\Requests\Admin\Group;

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
            'title' => 'required|string|max:15',
            'chair_id' => 'required|integer|exists:chairs,id',
            'student_id' => 'nullable|numeric|exists:students,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Это поле необходимо для заполнения',
            'title.max' => 'Название группы не должно превышать более :max символов',
            'semester.required' => 'Это поле необходимо для заполнения',
            'semester.numeric' => 'Номер семестра должен быть числом',
            'semester.min' => 'Номер семестра не должен быть меньше :min',
            'semester.max' => 'Номер семестра не должен быть больше :max',
        ];
    }
}
