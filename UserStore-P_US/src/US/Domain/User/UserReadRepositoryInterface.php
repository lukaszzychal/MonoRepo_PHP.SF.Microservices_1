<?php

declare(strict_types=1);

namespace App\US\Domain\User;

interface UserReadRepositoryInterface
{
    public function find(UserID $userId): ?User;
}
