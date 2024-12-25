<?php

namespace App\Controller;

use App\Service\SalesService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/sales', name: 'app_sales')]
class SalesController extends SalesService
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('sales/index.html.twig', $this->getModuleAndTableProperties(...$this->getProperties()));
    }
}
