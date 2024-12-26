<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/product', name: 'app_product')]
class ProductController extends ProductService
{
    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct($productRepository);
    }

    #[Route('/index', name: '_index', methods:['GET'])]
    public function index(): Response
    {
        $productForm = $this->createForm(ProductType::class, new Product);
        $parameters = array_merge(
            $this->getModuleAndTableProperties(...$this->getProperties()),
            compact('productForm')
        );
        return $this->render('product/index.html.twig', $parameters);
    }

    #[Route('/all', name: '_all', methods:['GET'])]
    public function allAction(): JsonResponse
    {
        $this->validateUserControllerAccess(self::class);
        return $this->json($this->all());
    }

    #[Route('/save/{id?}', name: '_save', methods:['POST', 'PUT', 'DELETE'])]
    public function saveAction(Product $product = null): JsonResponse
    {
        if($this->getRequest()->getMethod() === Request::METHOD_DELETE){
            $this->delete($product);
        }else{
            $this->save();
        }
        return $this->json($this->getResponse(), $this->getCode());
    }
}
