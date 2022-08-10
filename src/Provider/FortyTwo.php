<?php

namespace Mehdibo\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class FortyTwo extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl(): string
    {
        return "https://api.intra.42.fr/oauth/authorize";
    }

    /**
     * @param array<string, mixed> $params
     */
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

    /**
     * @param array<string, mixed>|string $data
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() !== 200) {
            $errorDescription = '';
            $error = '';
            if (\is_array($data) && !empty($data)) {
                $errorDescription = $data['error_description'] ?? $data['message'];
                $error = $data['error'];
            }
            throw new IdentityProviderException(
                sprintf("%d - %s: %s", $response->getStatusCode(), $error, $errorDescription),
                $response->getStatusCode(),
                $data
            );
        }
    }

    /**
     * @param array<string, mixed> $response
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwner
    {
        return new ResourceOwner($response);
    }
}
