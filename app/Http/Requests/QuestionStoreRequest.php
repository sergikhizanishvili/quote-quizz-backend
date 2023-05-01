<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function validator($factory)
    {
        return $factory->make(
            $this->sanitize(), $this->container->call([$this, 'rules']), $this->messages()
        );
    }

    public function sanitize()
    {
        $this->merge([
            'answers' => json_decode($this->input('answers'), true)
        ]);
        return $this->all();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(\App\Enums\QuestionTypeEnum::values())],
            'question' => ['required', 'string', 'max:255'],
            'correct' => ['required', 'string', 'max:5'],
            'answers' => ['exclude_if:type,binary', 'required', 'array', 'min:3', 'max:3'],
            'answers.*' => ['required', 'string', 'max:255'],
        ];
    }
}
