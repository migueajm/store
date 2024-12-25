<?php

namespace App\Controller;

use App\Service\StocktakingService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/stocktaking', name: 'app_stocktaking')]
class StocktakingController extends StocktakingService
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('stocktaking/index.html.twig', $this->getModuleAndTableProperties(...$this->getProperties()));
    }
}
