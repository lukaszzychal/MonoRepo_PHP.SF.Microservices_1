<?php

namespace App\US\Domain\Email;

use Stringable;
use Webmozart\Assert\Assert;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Email implements Stringable
{
    public function __construct(
        public readonly string $email
    ) {
        try {
            Assert::email($email, sprintf('The email %s is not a valid email.', $email));
        } catch (\InvalidArgumentException $e) {
            throw new InvalidEmailException($this->email);
        }
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
