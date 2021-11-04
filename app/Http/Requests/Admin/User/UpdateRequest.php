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
        // если обновляем студента
        if ($this->role_id == 1) {
            return $this->getRequestStudent($this->request->all())->rules();
        }
        // если обновляем преподавателя
        if ($this->role_id == 2) {
            return $this->getRequestTeacher($this->request->all())->rules();
        }
    }

    public function getRequestStudent($array)
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

    public function getRequestTeacher($array)
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
