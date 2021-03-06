<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Admin\User\Student\UpdateRequest as StudentUpdateRequest;
use App\Http\Requests\Admin\User\Teacher\UpdateRequest as TeacherUpdateRequest;
use App\Models\User;
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
            'avatar' => 'nullable|image',
            'phone_number' => 'nullable|regex:%^\\+\\d[-]\\(\\d{3}\\)[-]\\d{3}[-]\\d{2}[-]\\d{2}$%',
            'email' => ['required', 'string', 'email', 'unique:users,email,' . $this->user_id, 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'],
            'user_id' => 'required|numeric|exists:users,id',
            'role_id' => 'required|numeric',
        ];
        // если обновляем студента
        if ($this->role_id == User::ROLE_STUDENT) {
            return $userRules + $this->getStudentRules($this->request->all())->rules();
        }
        // если обновляем преподавателя
        if ($this->role_id == User::ROLE_TEACHER) {
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
