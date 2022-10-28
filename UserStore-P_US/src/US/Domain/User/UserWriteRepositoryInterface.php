<?php

namespace App\US\Domain\User;

interface UserWriteRepositoryInterface
{
    public function save(User $user): void;
}
