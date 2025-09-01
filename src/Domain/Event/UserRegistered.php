<?php
namespace App\Domain\Event;

use App\Domain\Model\User;

class UserRegistered
{
    public function __construct(public readonly User $user) {}
}
