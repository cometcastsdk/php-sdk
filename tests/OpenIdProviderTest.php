<?php

namespace Cometcast\Openapi\Tests;

use Cometcast\Openapi\OpenIdProvider;
use Cometcast\Openapi\OpenIdResourceOwner;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Cometcast\Openapi\OpenIdProvider
 */
class OpenIdProviderTest extends TestCase
{
    private $provider;
    private $baseUrl = 'https://example.com';

    protected function setUp(): void
    {
        $this->provider = new OpenIdProvider([
            'clientId' => 'test-client-id',
            'clientSecret' => 'test-client-secret',
            'redirectUri' => 'https://example.com/callback',
            'base_url' => $this->baseUrl,
            'pkce_method' => 'S256'
        ]);
    }

    public function testConstructorSetsBaseUrl(): void
    {
        $provider = new OpenIdProvider([
            'clientId' => 'test-client-id',
            'clientSecret' => 'test-client-secret',
            'redirectUri' => 'https://example.com/callback',
            'base_url' => 'https://custom.example.com'
        ]);

        $this->assertEquals(
            'https://custom.example.com/protocol/openid-connect/auth',
            $provider->getBaseAuthorizationUrl()
        );
    }

    public function testConstructorWithoutBaseUrl(): void
    {
        $provider = new OpenIdProvider([
            'clientId' => 'test-client-id',
            'clientSecret' => 'test-client-secret',
            'redirectUri' => 'https://example.com/callback'
        ]);

        $this->assertEquals(
            '/protocol/openid-connect/auth',
            $provider->getBaseAuthorizationUrl()
        );
    }

    public function testGetBaseAuthorizationUrl(): void
    {
        $expected = $this->baseUrl . '/protocol/openid-connect/auth';
        $this->assertEquals($expected, $this->provider->getBaseAuthorizationUrl());
    }

    public function testGetBaseAccessTokenUrl(): void
    {
        $expected = $this->baseUrl . '/protocol/openid-connect/token';
        $this->assertEquals($expected, $this->provider->getBaseAccessTokenUrl([]));
    }

    public function testGetResourceOwnerDetailsUrl(): void
    {
        $token = new AccessToken(['access_token' => 'test-token']);
        $expected = $this->baseUrl . '/protocol/openid-connect/userinfo';
        $this->assertEquals($expected, $this->provider->getResourceOwnerDetailsUrl($token));
    }

    public function testGetDefaultScopes(): void
    {
        $reflection = new \ReflectionClass($this->provider);
        $method = $reflection->getMethod('getDefaultScopes');
        $method->setAccessible(true);
        
        $scopes = $method->invoke($this->provider);
        $this->assertEquals(['openid'], $scopes);
    }

    public function testGetPkceMethodWithCustomMethod(): void
    {
        $provider = new OpenIdProvider([
            'clientId' => 'test-client-id',
            'clientSecret' => 'test-client-secret',
            'redirectUri' => 'https://example.com/callback',
            'base_url' => $this->baseUrl,
            'pkce_method' => 'plain'
        ]);

        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('getPkceMethod');
        $method->setAccessible(true);
        
        $pkceMethod = $method->invoke($provider);
        $this->assertEquals('plain', $pkceMethod);
    }

    public function testGetPkceMethodWithoutCustomMethod(): void
    {
        $provider = new OpenIdProvider([
            'clientId' => 'test-client-id',
            'clientSecret' => 'test-client-secret',
            'redirectUri' => 'https://example.com/callback',
            'base_url' => $this->baseUrl
        ]);

        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('getPkceMethod');
        $method->setAccessible(true);
        
        // 應該回傳父類別的預設值或 null
        $pkceMethod = $method->invoke($provider);
        $this->assertTrue($pkceMethod === null || is_string($pkceMethod));
    }

    public function testCheckResponseWithoutError(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $data = ['access_token' => 'test-token'];

        $reflection = new \ReflectionClass($this->provider);
        $method = $reflection->getMethod('checkResponse');
        $method->setAccessible(true);

        // 不應該拋出例外
        $this->assertNull($method->invoke($this->provider, $response, $data));
    }

    public function testCheckResponseWithError(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(400);
        
        $data = ['error' => 'invalid_request'];

        $reflection = new \ReflectionClass($this->provider);
        $method = $reflection->getMethod('checkResponse');
        $method->setAccessible(true);

        $this->expectException(IdentityProviderException::class);
        $this->expectExceptionMessage('invalid_request');
        $this->expectExceptionCode(400);

        $method->invoke($this->provider, $response, $data);
    }

    public function testCheckResponseWithErrorAndDescription(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(401);
        
        $data = [
            'error' => 'invalid_client',
            'error_description' => 'Client authentication failed'
        ];

        $reflection = new \ReflectionClass($this->provider);
        $method = $reflection->getMethod('checkResponse');
        $method->setAccessible(true);

        $this->expectException(IdentityProviderException::class);
        $this->expectExceptionMessage('invalid_client: Client authentication failed');
        $this->expectExceptionCode(401);

        $method->invoke($this->provider, $response, $data);
    }

    public function testCreateResourceOwner(): void
    {
        $token = new AccessToken(['access_token' => 'test-token']);
        $response = [
            'sub' => '12345',
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];

        $reflection = new \ReflectionClass($this->provider);
        $method = $reflection->getMethod('createResourceOwner');
        $method->setAccessible(true);

        $resourceOwner = $method->invoke($this->provider, $response, $token);

        $this->assertInstanceOf(OpenIdResourceOwner::class, $resourceOwner);
        $this->assertEquals('12345', $resourceOwner->getId());
        $this->assertEquals('John Doe', $resourceOwner->getName());
        $this->assertEquals('john@example.com', $resourceOwner->getEmail());
    }

    public function testCreateResourceOwnerWithEmptyResponse(): void
    {
        $token = new AccessToken(['access_token' => 'test-token']);
        $response = [];

        $reflection = new \ReflectionClass($this->provider);
        $method = $reflection->getMethod('createResourceOwner');
        $method->setAccessible(true);

        $resourceOwner = $method->invoke($this->provider, $response, $token);

        $this->assertInstanceOf(OpenIdResourceOwner::class, $resourceOwner);
        $this->assertNull($resourceOwner->getId());
        $this->assertNull($resourceOwner->getName());
        $this->assertNull($resourceOwner->getEmail());
    }
}