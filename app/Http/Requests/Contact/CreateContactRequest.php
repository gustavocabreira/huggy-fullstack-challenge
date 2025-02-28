<?php

namespace App\Http\Requests\Contact;

use App\Rules\CellphoneNumber;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class CreateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'email' => ['required', 'email', 'max:255'],
            'phone_number' => ['nullable', 'int', new PhoneNumber],
            'cellphone_number' => ['nullable', 'int', new CellphoneNumber],
            'address' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png'],
        ];
    }
}
