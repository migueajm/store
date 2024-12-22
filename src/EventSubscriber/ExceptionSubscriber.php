<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private RequestStack $requestStack;
    private LoggerInterface $logger;

    public function __construct(RequestStack $requestStack, LoggerInterface $logger)
    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }
    public function onOnKernelException(ExceptionEvent $event): void
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        $exception = $event->getThrowable();
        $response = new JsonResponse([
            'error' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'request' => [
                'method' => $currentRequest->getMethod(),
                'url' => $currentRequest->getUri(),
                'body' => json_decode($currentRequest->getContent(), true),
                'headers' => $currentRequest->headers->all(),
                'queryParams' => $currentRequest->query->all()
            ]
        ]);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(500);
        }

        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION  => 'onOnKernelException',
        ];
    }
}
