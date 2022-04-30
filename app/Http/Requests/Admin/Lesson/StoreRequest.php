<?php

namespace App\Http\Requests\Admin\Lesson;

use App\Http\Requests\Admin\User\StoreRequest as UserStoreRequest;
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
        return [
            'semester' => 'required|integer',
            'teacher_id' => 'required|integer|exists:teachers,id',
            'discipline_id' => 'required|integer|exists:disciplines,id',
            'group_id' => 'required|integer|exists:groups,id',
            'year_id' => 'required|integer|exists:years,id',
        ];
    }
}
