<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/product', name: 'app_product')]
class ProductController extends ProductService
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', $this->getModuleAndTableProperties(...$this->getProperties()));
    }
}
