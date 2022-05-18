<?php

namespace App\Http\Requests\Employee\News;

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
            'title' => 'required|string|max:255',
            'content' => 'required',
            'preview' => 'required|file|mimes:jpg,jpeg,png',
            'images.*' => 'nullable|image',
            'tags_ids' => 'nullable|array',
            'tags_ids.*' => 'nullable|exists:tags,id',
            'start_date' => 'nullable|date',
            'finish_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function messages()
    {
        return [
            'finish_date.after_or_equal' => 'Дата окончания должна быть равна дате проведения или больше её',
            'title.required' => 'Новость должна содержать заголовок',
            'content.required' => 'Напишите что-нибудь...',
            'preview.required' => 'Необходимо загрузить главное изображение (превью)',
            'preview.mimes' => 'Файл должен иметь один из следующих форматов: jpg,jpeg,png',
            'preview.file' => 'Необходимо загрузить изображение',
            'images.*.mimes' => 'Файл должен иметь один из следующих форматов: jpg,jpeg,png',
            'images.*.file' => 'Необходимо загрузить изображение'
        ];
    }
}
