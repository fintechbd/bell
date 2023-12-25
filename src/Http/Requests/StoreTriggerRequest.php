<?php

namespace Fintech\Bell\Http\Requests;

use Fintech\Bell\Models\Trigger;
use Illuminate\Foundation\Http\FormRequest;

class StoreTriggerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $uniqueRule = 'unique:' . config('fintech.bell.trigger_model', Trigger::class) . ',code';

        return [
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'code' => ['required', 'string', 'min:5', 'max:255', $uniqueRule],
            'enabled' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
