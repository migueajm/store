<?php

namespace App\EventSubscriber;

use App\Exception\ErrorApiException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private RequestStack $requestStack;
    private LoggerInterface $logger;
    private Environment $twig;
    private RouterInterface $router;
    private bool $isProduction;

    public function __construct(RequestStack $requestStack, LoggerInterface $logger, Environment $twig, RouterInterface $routerInterface)
    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
        $this->isProduction = $_ENV['APP_ENV'] === 'prod';
        $this->twig = $twig;
        $this->router = $routerInterface;
    }

    public function onOnKernelException(ExceptionEvent $event): void
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        $exception = $event->getThrowable();
        $response = new Response();
        $code = $exception->getCode();
        if($exception instanceof HttpExceptionInterface){
            $code = $exception->getStatusCode();
        }
        if($code < 100 || $code >= 600) $code = 500;
        $response->setStatusCode($code);
        $errorApi = new ErrorApiException($exception, $currentRequest, $code);
        $data = $errorApi->getError()->toArray();
        if($code >= 500 && $this->isProduction) {
            $this->logger->error('Exception caught by ExceptionListener', compact('exception'));
        }
        $errorDetail = [
            'isDev' => !$this->isProduction,
            'file' => $exception->getFile() . "Line: " . $exception->getLine()
        ];
        $url = $currentRequest->headers->get('referer', $this->router->generate('app_authentication_main'));
        $data = array_merge($data, compact('url', 'errorDetail'));
        $content = $this->twig->render('exception/exception.html.twig', $data);
        $response->setContent($content);
        $response->setStatusCode($data['code']);
        if($this->isProduction) {
            unset($data['request']);
            unset($data['errorDetail']);
            unset($data['url']);
        };
        if($this->isApiOrFetchRequestException($currentRequest)) $response = new JsonResponse($data, $code);
        $event->setResponse($response);
    }

    private function isApiOrFetchRequestException(Request $request)
    {
        return str_starts_with($request->getPathInfo(), '/api') || $request->isXmlHttpRequest();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION  => 'onOnKernelException',
        ];
    }
}
