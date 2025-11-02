<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MinWords implements ValidationRule
{
    public function __construct(protected int $minWords) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (str_word_count($value) < $this->minWords) {
            $fail("The :attribute must have at least {$this->minWords} words.");
        }
    }
}
