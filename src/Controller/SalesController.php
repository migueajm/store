<?php

namespace App\Controller;

use App\Service\AbstractService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/sales', name: 'app_sales')]
class SalesController extends AbstractService
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('sales/index.html.twig', [
            'controller_name' => 'SalesController',
        ]);
    }
}
