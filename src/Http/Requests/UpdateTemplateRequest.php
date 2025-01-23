<?php

namespace Fintech\Bell\Http\Requests;

use Fintech\Core\Enums\Bell\NotificationMedium;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTemplateRequest extends FormRequest
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
        $rules = [
            'trigger_code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'medium' => ['required', 'string', Rule::in(NotificationMedium::values())],
            'content' => ['required', 'array'],
            'enabled' => ['required', 'boolean'],
            'recipients' => ['array', 'required'],
            'recipients.admin' => ['boolean', 'required'],
            'recipients.agent' => ['boolean', 'required'],
            'recipients.customer' => ['boolean', 'required'],
            'recipients.extra' => ['array', 'required'],
            'recipients.extra.*' => ['nullable', 'string'],
        ];

        if ($this->input('medium') == 'sms') {
            $rules['content.body'] = ['required', 'string', 'max:255'];
            $rules['recipients.extra.*'][] = 'mobile';
        } elseif ($this->input('medium') == 'mail') {
            $rules['content.title'] = ['required', 'string', 'max:255'];
            $rules['content.body'] = ['required', 'string'];
            $rules['recipients.extra.*'][] = 'email:rfc,dns';
        } elseif ($this->input('medium') == 'push') {
            $rules['content.type'] = ['required', 'string', 'max:255'];
            $rules['content.title'] = ['required', 'string', 'max:255'];
            $rules['content.body'] = ['required', 'string'];
            $rules['content.image'] = ['nullable', 'string'];
        } elseif ($this->input('medium') == 'chat') {
            $rules['content.body'] = ['required', 'string'];
            $rules['content.type'] = ['required', 'string', 'max:255'];
            $rules['content.image'] = ['nullable', 'string'];
            $rules['recipients.extra.*'][] = 'integer';
        }

        return $rules;

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
