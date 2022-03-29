<?php

namespace App\Http\Requests\Employee\News;

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
            'title' => 'required|string|max:255',
            'content' => 'required',
            'preview' => 'nullable|file|mimes:jpg,jpeg,png',
            'images.*' => 'nullable|file|mimes:jpg,jpeg,png',
            'category_id' => 'required|exists:categories,id',
            'chair_id' => 'required|exists:chairs,id',
            'start_date' => 'nullable|date|after_or_equal:' . date('d.m.Y'),
            'finish_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function messages()
    {
        return [
            'start_date.after_or_equal' => 'Дата окончания должна быть равна текущей дате или больше её',
            'finish_date.after_or_equal' => 'Дата окончания должна быть равна дате проведения или больше её',
            'title.required' => 'Новость должна содержать заголовок',
            'content.required' => 'Напишите что-нибудь...',
            'preview.mimes' => 'Файл должен иметь один из следующих форматов: jpg,jpeg,png',
            'preview.file' => 'Необходимо загрузить изображение',
            'images.*.mimes' => 'Файл должен иметь один из следующих форматов: jpg,jpeg,png',
            'images.*.file' => 'Необходимо загрузить изображение'
        ];
    }
}
