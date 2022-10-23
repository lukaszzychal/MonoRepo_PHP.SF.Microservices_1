<?php

namespace App\US\Domain\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findUser(UserID $userId): ?User;
}
