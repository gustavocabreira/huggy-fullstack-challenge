<?php

namespace App\Http\Requests\Contact;

use App\Models\Contact;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'order_by' => ['sometimes', 'string', Rule::in((new Contact)->getFillable())],
            'direction' => ['sometimes', 'string', Rule::in(['asc', 'desc'])],
            'query  ' => ['sometimes', 'string'],
        ];
    }
}
