<?php

namespace App\Controller;

use App\Service\AbstractService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/report', name: 'app_report')]
class ReportController extends AbstractService
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }
}
