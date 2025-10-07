<?php

namespace Cometcast\Openapi\Tests\HttpClient\Partners\Livebuy\Channel;

use Cometcast\Openapi\HttpClient\OpenApiHttpClientFactory;
use Cometcast\Openapi\HttpClient\Partners\Livebuy\Channel\ChannelClient;
use Cometcast\Openapi\HttpClient\Partners\Livebuy\Channel\ConnectChannelRequest;
use Cometcast\Openapi\HttpClient\Result;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \Cometcast\Openapi\HttpClient\Partners\Livebuy\Channel\ChannelClient
 */
class ChannelClientTest extends TestCase
{
    private $httpFactory;
    private $httpClient;
    private $channelClient;

    protected function setUp(): void
    {
        $this->httpFactory = $this->createMock(OpenApiHttpClientFactory::class);
        $this->httpClient = $this->createMock(Client::class);
        $this->channelClient = new ChannelClient($this->httpFactory);
    }

    public function testConnectChannelSuccess(): void
    {
        // 準備測試資料
        $accessToken = 'test-access-token';
        $request = new ConnectChannelRequest();
        $request->name = 'Hello';
        $request->storeId = '123456';

        // 模擬 API 回應
        $responseData = [
            'result' => [
                'channel_id' => 1,
                'channel_name' => 'Hello',
                'store_id' => '123456'
            ]
        ];

        // 修正回應結構以符合實際程式碼中的 $responseJson->data 存取
        $apiResponseData = [
            'data' => [
                'channel_id' => 1,
                'channel_name' => 'Hello',
                'store_id' => '123456'
            ]
        ];

        $responseJson = json_encode($apiResponseData);

        // 建立 Mock Stream
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($responseJson);

        // 建立 Mock Response
        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn($stream);

        // 設定 HTTP Factory Mock
        $this->httpFactory
            ->expects($this->once())
            ->method('makeOpenApiAuthClient')
            ->with($accessToken)
            ->willReturn($this->httpClient);

        // 設定 HTTP Client Mock
        $this->httpClient
            ->expects($this->once())
            ->method('post')
            ->with(
                '/v1/partners/livebuy/channel/connect',
                [
                    'json' => $request->jsonSerialize()
                ]
            )
            ->willReturn($response);

        // 執行測試
        $result = $this->channelClient->connectChannel($request, $accessToken);

        // 驗證結果
        $this->assertInstanceOf(Result::class, $result);
        $this->assertIsArray($result['data']);
        $this->assertEquals(1, $result['data']['channel_id']);
        $this->assertEquals('Hello', $result['data']['channel_name']);
        $this->assertEquals('123456', $result['data']['store_id']);
    }

    public function testConnectChannelWithDifferentData(): void
    {
        // 準備不同的測試資料
        $accessToken = 'another-access-token';
        $request = new ConnectChannelRequest();
        $request->name = 'Test Channel';
        $request->storeId = '789012';

        // 模擬不同的 API 回應
        $apiResponseData = [
            'data' => [
                'channel_id' => 999,
                'channel_name' => 'Test Channel',
                'store_id' => '789012'
            ]
        ];

        $responseJson = json_encode($apiResponseData);

        // 建立 Mock Stream
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($responseJson);

        // 建立 Mock Response
        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn($stream);

        // 設定 HTTP Factory Mock
        $this->httpFactory
            ->expects($this->once())
            ->method('makeOpenApiAuthClient')
            ->with($accessToken)
            ->willReturn($this->httpClient);

        // 設定 HTTP Client Mock
        $this->httpClient
            ->expects($this->once())
            ->method('post')
            ->with(
                '/v1/partners/livebuy/channel/connect',
                [
                    'json' => $request->jsonSerialize()
                ]
            )
            ->willReturn($response);

        // 執行測試
        $result = $this->channelClient->connectChannel($request, $accessToken);

        // 驗證結果
        $this->assertInstanceOf(Result::class, $result);
        $this->assertIsArray($result['data']);
        $this->assertEquals(999, $result['data']['channel_id']);
        $this->assertEquals('Test Channel', $result['data']['channel_name']);
        $this->assertEquals('789012', $result['data']['store_id']);
    }

    public function testConnectChannelRequestSerialization(): void
    {
        // 測試 ConnectChannelRequest 的 JSON 序列化
        $request = new ConnectChannelRequest();
        $request->name = 'Hello';
        $request->storeId = '123456';

        $expectedJson = [
            'name' => 'Hello',
            'store_id' => '123456'
        ];

        $this->assertEquals($expectedJson, $request->jsonSerialize());
        $this->assertEquals(json_encode($expectedJson), json_encode($request));
    }

    // 已移除 ChannelInfo/ConnectChannelResponse 結構測試，改由 Result 測試涵蓋

    public function testConstructor(): void
    {
        // 測試建構函式
        $factory = $this->createMock(OpenApiHttpClientFactory::class);
        $client = new ChannelClient($factory);

        $this->assertInstanceOf(ChannelClient::class, $client);
    }

    public function testDisconnectChannelSuccess(): void
    {
        $accessToken = 'test-access-token';
        $channelId = 123;

        $response = $this->createMock(Response::class);
        $response->method('getStatusCode')->willReturn(204);

        $this->httpFactory
            ->expects($this->once())
            ->method('makeOpenApiAuthClient')
            ->with($accessToken)
            ->willReturn($this->httpClient);

        $this->httpClient
            ->expects($this->once())
            ->method('delete')
            ->with("/v1/partners/livebuy/channel/{$channelId}")
            ->willReturn($response);

        $result = $this->channelClient->disconnectChannel($channelId, $accessToken);

        $this->assertEquals(204, $result->getStatusCode());
    }
}