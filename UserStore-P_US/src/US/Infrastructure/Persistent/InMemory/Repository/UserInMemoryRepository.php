<?php

declare(strict_types=1);

namespace App\US\Infrastructure\Persistent\InMemory\Repository;

use App\US\Domain\User\User;
use App\US\Domain\User\UserID;

final class UserInMemoryRepository
{
    /** @phpstan-ignore-next-line */
    private array $memory;

    public function save(User $user): void
    {
        $this->memory[(string) $user->getUuid()->uuid] = $user;
    }

    public function findUser(UserID $userId): User
    {
        return $this->memory[(string) $userId->uuid];
    }
}
