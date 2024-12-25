<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\Response;

class Error
{
    private ?string $exception = null;
    private ?string $error = null;
    private ?int $code = null;
    private ?RequestError $request = null;
    private ?string $statusText = null;
    private ?array $formError = null;

    public function getException(): ?string
    {
        return $this->exception;
    }

    public function setException(string $exception): static
    {
        $this->exception = $exception;
        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(string $error): static
    {
        $this->error = $error;
        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): static
    {
        $this->code = $code;
        $this->setStatusText($code);
        return $this;
    }

    public function getRequest(): ?RequestError
    {
        return $this->request;
    }

    public function setRequest(?RequestError $request): static
    {
        $this->request = $request;
        return $this;
    }
    public function getStatusText(): ?string
    {
        return $this->statusText;
    }

    public function setStatusText(int $code): static
    {
        $this->statusText = Response::$statusTexts[$code] ?? Response::$statusTexts[500];
        return $this;
    }

    public function getFormError(): ?array
    {
        return $this->formError;
    }

    public function setFormError(array $formError): static
    {
        $this->formError = $formError;
        return $this;
    }

    public function toArray()
    {
        $error = get_object_vars($this);
        $error['request'] = $this->getRequest()?->toArray();
        return $error;
    }
}
