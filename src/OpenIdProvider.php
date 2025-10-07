<?php

namespace Cometcast\Openapi;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class OpenIdProvider extends AbstractProvider
{
    private $baseUrl;

    private $pkceMethod;

    public function __construct(array $options = [], array $collaborators = [])
    {
        parent::__construct($options, $collaborators);
        $this->baseUrl = $options['base_url'] ?? '';
        $this->pkceMethod = $options['pkce_method'] ?? '';
    }

    /**
     * Returns the list of options that can be passed to the HttpClient
     *
     * @param array $options An array of options to set on this provider.
     *     Options include `clientId`, `clientSecret`, `redirectUri`, and `state`.
     *     Individual providers may introduce more options, as needed.
     * @return array The options to pass to the HttpClient constructor
     */
    protected function getAllowedClientOptions(array $options)
    {
        return ['timeout', 'proxy', 'verify'];
    }

    public function getBaseAuthorizationUrl()
    {
        // TODO: Implement getBaseAuthorizationUrl() method.
        return "{$this->baseUrl}/protocol/openid-connect/auth";
    }

    protected function getAuthorizationHeaders($token = null)
    {
        return ['Authorization' => 'Bearer ' . $token];
    }

    protected function getPkceMethod()
    {
        return $this->pkceMethod ?: parent::getPkceMethod();
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        // TODO: Implement getBaseAccessTokenUrl() method.
        return "{$this->baseUrl}/protocol/openid-connect/token";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        // TODO: Implement getResourceOwnerDetailsUrl() method.
        return "{$this->baseUrl}/protocol/openid-connect/userinfo";
    }

    protected function getDefaultScopes()
    {
        // TODO: Implement getDefaultScopes() method.
        return ['openid'];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        // TODO: Implement checkResponse() method.
        if (!empty($data['error'])) {
            $error = $data['error'];
            if (isset($data['error_description'])) {
                $error .= ': '.$data['error_description'];
            }
            throw new IdentityProviderException($error, $response->getStatusCode(), $data);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        // TODO: Implement createResourceOwner() method.
        return new OpenIdResourceOwner($response);
    }
}