<?php

namespace App\Controller;

use App\Service\AbstractService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/product', name: 'app_product')]
class ProductController extends AbstractService
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
