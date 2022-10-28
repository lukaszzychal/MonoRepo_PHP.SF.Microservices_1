<?php

declare(strict_types=1);

namespace App\US\Domain\User;

interface UserWriteRepositoryInterface
{
    public function save(User $user): void;
}
