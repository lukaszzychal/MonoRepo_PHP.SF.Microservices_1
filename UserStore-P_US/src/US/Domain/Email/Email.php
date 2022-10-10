<?php

namespace App\US\Domain\Email;

use Stringable;
use Webmozart\Assert\Assert;

final class Email implements Stringable
{
    public function __construct(
        public readonly string $value
    ) {
        try {
            Assert::email($value, sprintf('The email %s is not a valid email.', $value));
        } catch (\InvalidArgumentException $e) {
            throw new InvalidEmailException($this->value);
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
