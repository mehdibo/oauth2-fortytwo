<?php

namespace Mehdibo\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\ResponseInterface;

class FortyTwo extends AbstractProvider
{

    public function getBaseAuthorizationUrl(): string
    {
        return "https://api.intra.42.fr/oauth/authorize";
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return "https://api.intra.42.fr/oauth/token";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return "https://api.intra.42.fr/v2/me";
    }

    /**
     * @return string[]
     */
    protected function getDefaultScopes(): array
    {
        return ["public"];
    }

    protected function getScopeSeparator(): string
    {
        return " ";
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() !== 200) {
            $errorDescription = $data['error_description'] ?? $data['message'];
            throw new IdentityProviderException(
                sprintf("%d - %s: %s", $response->getStatusCode(), $data['error'], $errorDescription),
                $response->getStatusCode(),
                $response
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwner
    {
        return new ResourceOwner($response);
    }

    protected function getAuthorizationHeaders($token = null): array
    {
        $stringToken = $token;
        if ($token instanceof AccessTokenInterface) {
            $stringToken = $token->getToken();
        }
        return [
            'Authorization' => 'Bearer '.$stringToken
        ];
    }
}