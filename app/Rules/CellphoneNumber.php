<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CellphoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^\d{2}9\d{8}$/', $value)) {
            $fail('The '.$attribute.' field must start with a area code, followed by a 9, and then 9 more digits.');

            return;
        }

        if (! preg_match('/^\d{11}$/', $value)) {
            $fail('The '.$attribute.' field must be a valid phone number with 11 digits.');
        }
    }
}
