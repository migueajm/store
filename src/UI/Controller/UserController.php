<?php
namespace App\Presentation\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Application\Command\RegisterUserCommand;
use App\Application\Handler\RegisterUserHandler;

class UserController
{
    public function __construct(private RegisterUserHandler $handler) {}

    public function register(Request $request): Response
    {
        $command = new RegisterUserCommand(
            $request->get('email'),
            $request->get('password')
        );

        ($this->handler)($command);

        return new Response("Usuario registrado con éxito");
    }
}
