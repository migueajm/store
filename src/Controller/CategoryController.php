<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin/category', name: 'app_category')]
class CategoryController extends CategoryService
{
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        parent::__construct($entityManagerInterface);
    }

    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        $categoryForm = $this->createForm(CategoryType::class, new Category());
        $parameters = array_merge(
            $this->getModuleAndTableProperties(...$this->getProperties()),
            compact('categoryForm')
        );
        return $this->render('category/index.html.twig', $parameters);
    }

    #[Route('/all', name: '_all', methods:['GET'])]
    public function allAction(): JsonResponse
    {
        $this->validateUserControllerAccess(self::class);
        return $this->json($this->all());
    }

    #[Route('/save/{id?}', name: '_save', methods:['POST', 'PUT', 'DELETE'])]
    public function saveAction(ValidatorInterface $validatorInterface, ?Category $category = null): JsonResponse
    {
        if($this->getRequest()->getMethod() === Request::METHOD_DELETE){
            if(!$category instanceof Category){
                throw new BadRequestException("Undefined category.", 400);
            }
            $this->delete($category);
        }else{
            $this->save($validatorInterface, $category);
        }
        return $this->json($this->getRes(), $this->getCode());
    }
}
