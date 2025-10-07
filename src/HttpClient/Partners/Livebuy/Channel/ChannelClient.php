<?php
/**
 * [Partner-Only] Livebuy 直播頻道API
 */
namespace Cometcast\Openapi\HttpClient\Partners\Livebuy\Channel;

use Cometcast\Openapi\HttpClient\OpenApiHttpClientFactory;
use Cometcast\Openapi\HttpClient\Result;
use GuzzleHttp\Exception\GuzzleException;

class ChannelClient
{
    private $httpFactory;

    public function __construct(OpenApiHttpClientFactory $clientFactory)
    {
        $this->httpFactory = $clientFactory;
    }

    /**
     * 連接 Channel
     * @param ConnectChannelRequest $payload
     * @param string $accessToken 存取金鑰
     * @return Result
     * @throws GuzzleException
     */
    public function connectChannel(ConnectChannelRequest $payload, string $accessToken): Result
    {
        $httpClient = $this->httpFactory->makeOpenApiAuthClient($accessToken);

        $httpResponse = $httpClient->post('/v1/partners/livebuy/channel/connect', [
            'json' => $payload,
        ]);

        $responseJson = json_decode($httpResponse->getBody()->getContents(), true);

        return new Result($responseJson);
    }

    /**
     * @throws GuzzleException
     */
    public function disconnectChannel($channelId, $accessToken): \Psr\Http\Message\ResponseInterface
    {
        $httpClient = $this->httpFactory->makeOpenApiAuthClient($accessToken);

        return $httpClient->delete("/v1/partners/livebuy/channel/{$channelId}");
    }
}