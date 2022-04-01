<?php

namespace Tests\App\Test\HttpClient;

use Symfony\Component\HttpClient\MockHttpClient as BaseMockHttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class MockHttpClient extends BaseMockHttpClient
{
    public array $requests = [];
    public array $allowedResponses = [];

    public function __construct(ResponseFactory $responseFactory)
    {
        parent::__construct($responseFactory, 'https://fake.code');
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $this->requests[] = [
            'method' => $method,
            'url' => $url,
            'options' => $options,
            'response' => $response = parent::request($method, $url, $options),
        ];

        return $response;
    }
}
