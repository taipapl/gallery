<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OneEmail implements ValidationRule
{

    public $tagId;

    public function __construct($tagId)
    {
        $this->tagId = $tagId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = \App\Models\Email::where('email', $value)->first();

        if ($email) {
            $usersTags = \App\Models\pivot\UsersTags::where('email_id', $email->id)->where('tag_id', $this->tagId)->first();

            if ($usersTags) {
                $fail('This email is already shared with this tag.');
            }
        }
    }
}