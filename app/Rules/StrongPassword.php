<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class StrongPassword implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < 8) {
            $fail('A senha deve ter pelo menos 8 caracteres.');
        }
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('A senha deve conter pelo menos uma letra maiúscula.');
        }
        if (!preg_match('/[a-z]/', $value)) {
            $fail('A senha deve conter pelo menos uma letra minúscula.');
        }
        if (!preg_match('/\d/', $value)) {
            $fail('A senha deve conter pelo menos um número.');
        }
        if (!preg_match('/[@$!%*?&]/', $value)) {
            $fail('A senha deve conter pelo menos um caractere especial (@$!%*?&).');
        }
    }
}
