<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Admin\User\Student\StoreRequest as StudentStoreRequest;
use App\Http\Requests\Admin\User\Teacher\StoreRequest as TeacherStoreRequest;
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
            'avatar' => 'nullable|string',
            'phone_number' => 'nullable|regex:%^\\+\\d[-]\\(\\d{3}\\)[-]\\d{3}[-]\\d{2}[-]\\d{2}$%',
            'email' => ['required', 'string', 'email', 'unique:users', 'regex:/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/'],
            'password' => 'required|string',
            'role_id' => 'required',
        ];
        // если добавляем студента
        if ($this->role_id == 1) {
            return $userRules + $this->getStudentRules($this->request->all())->rules();
        }
        // если добавляем преподавателя
        if ($this->role_id == 2) {
            return $userRules + $this->getTeacherRules($this->request->all())->rules();
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
}
