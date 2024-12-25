<?php

namespace App\Controller;

use App\Service\AbstractService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/stocktaking', name: 'app_stocktaking')]
class StocktakingController extends AbstractService
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('stocktaking/index.html.twig', [
            'controller_name' => 'StocktakingController',
        ]);
    }
}
