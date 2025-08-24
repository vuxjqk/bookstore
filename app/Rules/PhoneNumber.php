<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regex = '/^(?:\+84|0)(3[2-9]|7[0|6-9]|8[1-9]|9[0-9])[0-9]{7}$/';
        if (!preg_match($regex, $value)) {
            $fail($attribute . ' is not a valid phone number.');
        }
    }
}
