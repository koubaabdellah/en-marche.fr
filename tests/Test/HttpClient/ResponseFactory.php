<?php

namespace Tests\App\Test\HttpClient;

use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ResponseFactory
{
    private ?string $defaultResponse = null;
    private array $allowedResponses = [];

    public function __invoke(string $method, string $url, array $options = []): ResponseInterface
    {
        return new MockResponse($this->allowedResponses[$method.' '.parse_url($url, \PHP_URL_PATH)] ?? $this->defaultResponse);
    }

    public function addAllowedResponse(string $key, string $value): self
    {
        $this->allowedResponses[$key] = $value;

        return $this;
    }

    public function setDefaultResponse(?string $response): self
    {
        $this->defaultResponse = $response;

        return $this;
    }
}
