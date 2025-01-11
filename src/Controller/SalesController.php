<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Entity\SaleDetail;
use App\Form\SaleDetailType;
use App\Form\SaleType;
use App\Service\SalesService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/app/sales', name: 'app_sales')]
class SalesController extends SalesService
{
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        parent::__construct($entityManagerInterface);
    }

    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        $this->validateUserControllerAccess(self::class);
        $parameters = $this->getModuleAndTableProperties(...$this->getProperties());
        if(!$this->isAdminBool()){
            $sale = new Sale();
            $sale->setUser($this->getUser())
                ->setSaleDate(new DateTimeImmutable())
                ->setTotalAmount("0.0")
                ->setPaymentMethod('Efectivo');
            $parameters['isUser'] = true;
            $parameters['templatePath'] = 'sale/index.html.twig';
            $parameters['tableProduct'] = $this->getTableProductProperties();
            $parameters['formSaleDetail'] = $this->createFormView(SaleDetailType::class, new SaleDetail());
        }
        return $this->render('components/base.dashboard.html.twig', $parameters);
    }

    #[Route('/all', name: '_all', methods:['GET'])]
    public function allAction(): JsonResponse
    {
        $this->validateUserControllerAccess(self::class);
        return $this->json($this->all());
    }

    #[Route('/save/{id?}', name: '_save', methods:['POST', 'PUT', 'DELETE'])]
    public function saveAction(ValidatorInterface $validatorInterface, ?Sale $sale = null): JsonResponse
    {
        $this->validateUserControllerAccess(self::class);
        if($this->getRequest()->getMethod() === Request::METHOD_DELETE){
            if(!$sale instanceof Sale){
                throw new BadRequestException("Undefined category.", 400);
            }
            $this->delete($sale);
        }else{
            $this->save($validatorInterface, SaleType::class, $sale);
        }
        return $this->json($this->getRes(), $this->getCode());
    }

    #[Route('/generate', name: '_generate', methods:['POST'])]
    public function generateAction(): JsonResponse
    {
        $this->validateUserControllerAccess(self::class);
        $id = $this->generate();
        return $this->json(compact('id'));
    }

    #[Route('/detail/all', name: '_detail_all', methods:['GET'])]
    public function detailByIdAction(): JsonResponse
    {
        $this->validateUserControllerAccess(self::class);
        $saleId = $this->getRequest()->query->get('sale');
        if(!$saleId){
            throw new BadRequestException("Undefined \"sale id\" property", 400);
        }
        return $this->json($this->findDetailBySaleId($saleId));
    }

    #[Route('/detail/save/{id?}', name: '_detail_save', methods:['POST', 'PUT', 'DELETE'])]
    public function saveDetailAction(ValidatorInterface $validatorInterface, ?SaleDetail $saleDetail = null): JsonResponse
    {
        $this->validateUserControllerAccess(self::class);
        if($this->getRequest()->getMethod() === Request::METHOD_DELETE){
            if(!$saleDetail instanceof SaleDetail){
                throw new BadRequestException("Undefined sale detail.", 400);
            }
            $this->deleteDetail($saleDetail);
        }else{
            $this->saveDetail($validatorInterface, $saleDetail);
        }
        return $this->json($this->getRes(), $this->getCode());
    }
}
