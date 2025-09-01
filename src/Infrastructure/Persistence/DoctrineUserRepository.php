<?php
namespace App\Infrastructure\Persistence;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;

class DoctrineUserRepository implements UserRepository
{
    public function save(User $user): void
    {
        // Aquí iría Doctrine EntityManager->persist($user) ...
        echo "Usuario guardado en DB: " . $user->getEmail();
    }

    public function findByEmail(string $email): ?User
    {
        // Simulación: buscar en DB
        return null;
    }
}
