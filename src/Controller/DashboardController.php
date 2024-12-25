<?php

namespace App\Controller;

use App\Service\DashboardService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/dashboard', name: 'app_dashboard')]
class DashboardController extends DashboardService
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        die();
        $this->validateUserControllerAccess(self::class);
        $user = $this->getUser();
        $username = $user->getUserIdentifier();
        $modules = self::MODULES;
        $miniCard = [];
        $cards = [];
        return $this->render('dashboard/index.html.twig', compact('modules', 'username', 'miniCard', 'cards'));
    }
}
