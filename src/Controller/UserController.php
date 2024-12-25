<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user', name: 'app_user')]
class UserController extends UserService
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', $this->getModuleAndTableProperties(...$this->getProperties()));
    }
}
