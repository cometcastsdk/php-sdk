<?php

namespace Cometcast\Openapi\HttpClient;


use GuzzleHttp\Client;

class OpenApiHttpClientFactory
{
    private $openApiBaseUrl = "";

    private $sslVerify = true;
    public function __construct($openApiBaseUrl)
    {
        $this->openApiBaseUrl = $openApiBaseUrl;
    }

    public function setSSLVerify($bool)
    {
        $this->sslVerify = $bool;
        return $this;
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
            'verify' => $this->sslVerify,  // 開發可用 略過 SSL
        ]);
    }
}