<?php
namespace App\Domain\Model;

use App\Domain\ValueObject\Email;

class User
{
    private string $id;
    private Email $email;
    private string $passwordHash;

    public function __construct(string $id, Email $email, string $passwordHash)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function getId(): string { return $this->id; }
    public function getEmail(): Email { return $this->email; }
    public function getPasswordHash(): string { return $this->passwordHash; }
}
