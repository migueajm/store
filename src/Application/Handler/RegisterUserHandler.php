<?php
namespace App\Application\Handler;

use App\Application\Command\RegisterUserCommand;
use App\Application\Service\UserPasswordHasher;
use App\Domain\Model\User;
use App\Domain\ValueObject\Email;
use App\Domain\Repository\UserRepository;

class RegisterUserHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasher $passwordHasher
    ) {}

    public function __invoke(RegisterUserCommand $command): void
    {
        $email = new Email($command->email);
        $passwordHash = $this->passwordHasher->hash($command->plainPassword);

        $user = new User(uniqid('', true), $email, $passwordHash);
        $this->userRepository->save($user);
    }
}
