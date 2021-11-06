<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Admin\User\Student\UpdateRequest as StudentUpdateRequest;
use App\Http\Requests\Admin\User\Teacher\UpdateRequest as TeacherUpdateRequest;
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
        $userRules = [
            'surname' => 'required|string',
            'name' => 'required|string',
            'patronymic' => 'nullable|string',
            'avatar' => 'nullable|string',
            'phone_number' => 'nullable|regex:%^\\+\\d[-]\\(\\d{3}\\)[-]\\d{3}[-]\\d{2}[-]\\d{2}$%',
            'email' => ['required', 'string', 'email', 'unique:users,email,' . $this->user_id, 'regex:/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/'],
            'user_id' => 'required|numeric|exists:users,id',
            'role_id' => 'required|numeric',
        ];
        // если обновляем студента
        if ($this->role_id == 1) {
            return $userRules + $this->getStudentRules($this->request->all())->rules();
        }
        // если обновляем преподавателя
        if ($this->role_id == 2) {
            return $userRules + $this->getTeacherRules($this->request->all())->rules();
        }
    }

    public function getStudentRules($array)
    {
        return new StudentUpdateRequest(
            $this->query(),
            $array,
            $this->attributes(),
            $this->cookies->all(),
            $this->files->all(),
            $this->server(),
            $this->content,
        );
    }

    public function getTeacherRules($array)
    {
        return new TeacherUpdateRequest(
            $this->query(),
            $array,
            $this->attributes(),
            $this->cookies->all(),
            $this->files->all(),
            $this->server(),
            $this->content,
        );
    }
}
