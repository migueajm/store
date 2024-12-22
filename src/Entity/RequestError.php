<?php

namespace App\Entity;


class RequestError
{
    private ?string $method = null;
    private ?string $url = null;
    private ?array $body = null;
    private ?array $headers = null;
    private ?array $queryParams = null;

    public function __construct(
        ?string $method = null,
        ?string $url = null,
        ?array $body = null,
        ?array $headers = null,
        ?array $queryParams = null
    )
    {
        $this->setMethod($method)
            ->setUrl($url)
            ->setBody($body)
            ->setHeaders($headers)
            ->setQueryParams($queryParams);
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): static
    {
        $this->method = $method;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?array $body): static
    {
        $this->body = $body;
        return $this;
    }

    public function getHeaders(): ?string
    {
        return $this->headers;
    }

    public function setHeaders(?array $headers): static
    {
        $this->headers = $headers;
        return $this;
    }

    public function getQueryParams(): ?string
    {
        return $this->queryParams;
    }

    public function setQueryParams(?array $queryParams): static
    {
        $this->queryParams = $queryParams;

        return $this;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
