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
        $this->validateUserControllerAccess(self::class);
        $miniCard = [
            ['card' => ['body' => 'x1']],
            ['card' => ['body' => 'x1']],
            ['card' => ['body' => 'x1']],
            ['card' => ['body' => 'x1']]
        ];
        $cards = [
            ['card' => ['body' => 'x1']],
            ['card' => ['body' => 'x1']],
            ['card' => ['body' => 'x1']],
            ['card' => ['body' => 'x1']],
            ['card' => ['body' => 'x1']],
            ['card' => ['body' => 'x1']],
        ];
        return $this->render('dashboard/index.html.twig', compact('miniCard', 'cards'));
    }
}
