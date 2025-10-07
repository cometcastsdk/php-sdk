<?php

namespace Cometcast\Openapi\HttpClient;


use GuzzleHttp\Client;

class OpenApiHttpClientFactory
{
    private $openApiBaseUrl = "";

    public function __construct($openApiBaseUrl)
    {
        $this->openApiBaseUrl = $openApiBaseUrl;
    }

    public function makeOpenApiAuthClient($accessToken)
    {
        return new Client([
            'base_uri' => $this->openApiBaseUrl,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken
            ],
                'verify' => false,  // 開發可用 略過 SSL
        ]);
    }
}