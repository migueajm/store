<?php

namespace App\Controller;

use App\Service\StocktakingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/stocktaking', name: 'app_stocktaking')]
class StocktakingController extends StocktakingService
{
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        parent::__construct($entityManagerInterface);
    }

    #[Route('/index', name: '_index', methods:['GET'])]
    public function index(): Response
    {
        $this->validateUserControllerAccess(self::class);
        return $this->render('stocktaking/index.html.twig', $this->getModuleAndTableProperties(...$this->getProperties()));
    }

    #[Route('/admin/index', name: '_admin_index', methods:['GET'])]
    public function indexAdmin(): Response
    {
        $this->isAdmin();
        return $this->render('stocktaking/index.html.twig', $this->getModuleAndTableProperties(...$this->getProperties(true)));
    }

    #[Route('/all', name:'_all', methods:['GET'])]
    public function allAction(): JsonResponse
    {
        $this->isAdmin();
        return $this->json($this->all());
    }

    #[Route('/stock/all', name:'_stock_all', methods:['GET'])]
    public function stockAllAction(): JsonResponse
    {
        return $this->json($this->stockAll());
    }
}
