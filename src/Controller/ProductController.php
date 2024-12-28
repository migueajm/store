<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin/product', name: 'app_product')]
class ProductController extends ProductService
{
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        parent::__construct($entityManagerInterface);
    }

    #[Route('/index', name: '_index', methods:['GET'])]
    public function index(): Response
    {
        $this->validateUserControllerAccess(self::class);
        $parameters = $this->getModuleAndTableProperties(...$this->getProperties());
        return $this->render('components/base.dashboard.html.twig', $parameters);
    }

    #[Route('/all', name: '_all', methods:['GET'])]
    public function allAction(): JsonResponse
    {
        $this->validateUserControllerAccess(self::class);
        return $this->json($this->all());
    }

    #[Route('/save/{id?}', name: '_save', methods:['POST', 'PUT', 'DELETE'])]
    public function saveAction(ValidatorInterface $validatorInterface, ?Product $product = null): JsonResponse
    {
        $this->validateUserControllerAccess(self::class);
        if($this->getRequest()->getMethod() === Request::METHOD_DELETE){
            if(!$product instanceof Product){
                throw new BadRequestException("Undefined product.", 400);
            }
            $this->delete($product);
        }else{
            $this->save($validatorInterface, ProductType::class, $product);
        }
        return $this->json($this->getRes(), $this->getCode());
    }
}
