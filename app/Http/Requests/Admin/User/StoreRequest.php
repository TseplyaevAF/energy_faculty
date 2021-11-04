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
        // если добавляем студента
        if ($this->role_id == 1) {
            return $this->getRequestStudent($this->request->all())->rules();
        }
        // если добавляем преподавателя
        if ($this->role_id == 2) {
            return $this->getRequestTeacher($this->request->all())->rules();
        }
    }

    public function getRequestStudent($array)
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

    public function getRequestTeacher($array)
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
