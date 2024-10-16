<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'unique:' . Article::class . ',title'],
            'language' => ['required', 'string', 'size:2'],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => __('Название статьи не может быть пустым'),
            'title.unique' => __('Такая статья уже импортирована'),
        ];
    }
}
