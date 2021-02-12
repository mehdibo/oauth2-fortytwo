<?php

namespace Mehdibo\OAuth2\Client\Test;

use GuzzleHttp\ClientInterface;
use League\OAuth2\Client\Token\AccessToken;
use Mehdibo\OAuth2\Client\Provider\FortyTwo;
use Mehdibo\OAuth2\Client\Provider\ResourceOwner;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class FortyTwoTest extends TestCase
{
    private FortyTwo $provider;

    protected function setUp(): void
    {
        $this->provider = new FortyTwo([
             'clientId' => 'mock_client_id',
             'clientSecret' => 'mock_secret',
             'redirectUri' => 'mock_redirect_uri',
        ]);
    }

    /**
     * @param string $responseBody
     * @param int $statusCode
     * @return MockObject<ResponseInterface>
     */
    private function createMockResponse(string $responseBody, int $statusCode = 200): MockObject
    {
        $response = $this->createMock(ResponseInterface::class);

        $response->method('getStatusCode')
            ->willReturn($statusCode);

        $response->method('getBody')
            ->willReturn($responseBody);

        return $response;
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('/oauth/token', $uri['path']);
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('/oauth/authorize', $uri['path']);
    }

    public function testGetAccessTokenWithAuthorizationCode()
    {
        $response = $this->createMockResponse(<<<JSON
{
  "access_token": "mock_access_token",
  "token_type": "bearer",
  "refresh_token": "mock_refresh_token",
  "expires_in": 7200,
  "scope": "public",
  "created_at": 1613125557
}
JSON);

        $client = $this->createMock(ClientInterface::class);
        $client->method('send')
            ->willReturn($response);


        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertLessThanOrEqual(time() + 7200, $token->getExpires());
        $this->assertGreaterThanOrEqual(time(), $token->getExpires());
        $this->assertNull($token->getResourceOwnerId(), "42 Intranet doesn't return a resource owner");
    }

    public function testGetAccessTokenWithClientCredentials()
    {
        $response = $this->createMockResponse(<<<JSON
{
  "access_token": "mock_access_token",
  "token_type": "bearer",
  "expires_in": 7200,
  "scope": "public",
  "created_at": 1613125557
}
JSON);
        $client = $this->createMock(ClientInterface::class);
        $client->method('send')
            ->withConsecutive([])
            ->willReturn($response);


        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('client_credentials');

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertNull($token->getRefreshToken());
        $this->assertLessThanOrEqual(time() + 7200, $token->getExpires());
        $this->assertGreaterThanOrEqual(time(), $token->getExpires());
        $this->assertNull($token->getResourceOwnerId(), "42 Intranet doesn't return a resource owner");
    }

    public function testGetResourceOwner(): void
    {
        $response = $this->createMockResponse(<<<JSON
{
  "id": 42,
  "login": "ncat",
  "email": "test@email.com",
  "first_name": "Norminet",
  "last_name": "Cat",
  "image_url": "image_url",
  "staff?": false
}
JSON);

        $client = $this->createMock(ClientInterface::class);
        $client->method('send')
            ->willReturn($response);

        $this->provider->setHttpClient($client);

        $accessToken = $this->createMock(AccessToken::class);
        $accessToken->method('getToken')
            ->willReturn('mock_access_token');

        $resourceOwner = $this->provider->getResourceOwner($accessToken);
        $this->assertInstanceOf(ResourceOwner::class, $resourceOwner);
        $this->assertEquals([
            'id' => 42,
            'login' => 'ncat',
            'email' => 'test@email.com',
            'first_name' => 'Norminet',
            'last_name' => 'Cat',
            'image_url' => 'image_url',
            'staff?' => false,
        ], $resourceOwner->toArray());
    }

}