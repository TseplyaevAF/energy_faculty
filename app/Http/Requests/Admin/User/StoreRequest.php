<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Admin\User\Employee\StoreRequest as EmployeeStoreRequest;
use App\Http\Requests\Admin\User\Student\StoreRequest as StudentStoreRequest;
use App\Http\Requests\Admin\User\Teacher\StoreRequest as TeacherStoreRequest;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'unique:users', 'regex:/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/'],
            'password' => 'required|string',
            'role_id' => 'required|integer',
        ];
        // если добавляем студента
        if ($this->role_id == User::ROLE_STUDENT) {
            return $userRules + $this->getStudentRules($this->request->all())->rules();
        }
        // если добавляем преподавателя
        if ($this->role_id == User::ROLE_TEACHER) {
            return $userRules + $this->getTeacherRules($this->request->all())->rules();
        }
        // если добавляем сотрудника
        if ($this->role_id == User::ROLE_EMPLOYEE) {
            return $userRules + $this->getEmployeeRules($this->request->all())->rules();
        }
    }

    public function getStudentRules($array)
    {
        return new StudentStoreRequest(
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
        return new TeacherStoreRequest(
            $this->query(),
            $array,
            $this->attributes(),
            $this->cookies->all(),
            $this->files->all(),
            $this->server(),
            $this->content,
        );
    }

    public function getEmployeeRules($array)
    {
        return new EmployeeStoreRequest(
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
