<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'auto_approval' => ['required', 'boolean'],
            'about' => ['nullable']
        ];
    }

    protected function passedValidation(): void
    {
        // Access the validated data using the validated() method
        $data = $this->validated();

        // Modify the 'about' field, for example, convert it to uppercase
        $data['about'] = nl2br($data['about']);

        // Update the request data with the modified value
        $this->replace($data);
    }
}
