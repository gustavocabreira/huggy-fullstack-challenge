<?php

namespace App\Http\Requests\Contact;

use App\Rules\CellphoneNumber;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

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
            'address' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:2'],
            'country' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                $this->uniqueUserRule('email'),
                'required_without_all:phone_number,cellphone_number',
            ],
            'phone_number' => [
                'nullable',
                'int',
                new PhoneNumber,
                $this->uniqueUserRule('phone_number'),
                'required_without_all:email,cellphone_number',
            ],
            'cellphone_number' => [
                'nullable',
                'int',
                new CellphoneNumber,
                $this->uniqueUserRule('cellphone_number'),
                'required_without_all:email,phone_number',
            ],
        ];
    }

    private function uniqueUserRule(string $attribute): Unique
    {
        return Rule::unique('contacts')
            ->where(function ($query) use ($attribute) {
                return $query->where('user_id', auth()->user()->id)
                    ->where($attribute, $this->input($attribute));
            });
    }
}
