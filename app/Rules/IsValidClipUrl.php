<?php

namespace App\Rules;

use App\Services\Interfaces\TwitchServiceInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsValidClipUrl implements ValidationRule
{
    private TwitchServiceInterface $twitchService;

    public function __construct()
    {
        $this->twitchService = app(TwitchServiceInterface::class);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $id = $this->twitchService->extractTwitchClipId($value);

        if ($id === null) {
            $fail('Nieprawidłowy link do klipu.');

            return;
        }
    }
}
