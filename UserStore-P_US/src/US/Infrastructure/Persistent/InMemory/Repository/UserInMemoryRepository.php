<?php

namespace App\US\Infrastructure\Persistent\InMemory\Repository;

use App\US\Domain\User\User;
use App\US\Domain\User\UserID;

use Doctrine\Common\Collections\Collection;

class UserInMemoryRepository
{

    private Collection $memory;

    public function save(User $entity): void
    {
        $this->memory->add($entity);
    }

    public function findUser(UserID $userId): User
    {
        return $this->memory->get((string) $userId);
    }
}
