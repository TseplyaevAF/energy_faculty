<?php

namespace App\Http\Requests\Personal\Task;

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
    {;
        return [
            'group_discipline_id' => 'integer|exists:group_disciplines,id',
            'task' => 'required|mimes:doc,docx,rar,zip,xlsx,xls,pdf,txt',
        ];
    }
}
