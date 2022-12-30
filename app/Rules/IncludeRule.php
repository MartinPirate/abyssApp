<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IncludeRule implements Rule
{
    /**
     * @var array
     */
    protected array $allowedIncludes;

    /**
     * Create a new rule instance.
     *
     * @param array $allowedIncludes
     */
    public function __construct(array $allowedIncludes)
    {
        $this->allowedIncludes = $allowedIncludes;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $includes = explode(',', $value);
        foreach ($includes as $includeName) {
            if (!in_array($includeName, $this->allowedIncludes)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute contains a value not allowed';
    }
}
