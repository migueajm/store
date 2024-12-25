<?php

namespace App\Controller;

use App\Service\ReportService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/report', name: 'app_report')]
class ReportController extends ReportService
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('report/index.html.twig', $this->getModuleAndTableProperties(...$this->getProperties()));
    }
}
