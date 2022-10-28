<?php

namespace App\US\Domain\User;

interface UserReadRepositoryInterface
{
    public function find(UserID $userId): ?User;
}
