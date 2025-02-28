<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^\d{10}$/', $value)) {
            $fail('The '.$attribute.' field must be a valid phone number with 10 digits.');
        }
    }
}
