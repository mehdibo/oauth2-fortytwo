<?php

namespace Mehdibo\OAuth2\Client\Test;

use GuzzleHttp\ClientInterface;
use Mehdibo\OAuth2\Client\Provider\FortyTwo;
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

    public function testGetAccessToken()
    {
        $response = $this->createMock(ResponseInterface::class);

        $response->method('getHeader')
            ->withConsecutive([])
            ->willReturn('application/json');

        $response->method('getBody')
            ->withConsecutive([])
            ->willReturn('{}'); // TODO: set expected response body

        $client = $this->createMock(ClientInterface::class);
        $client->method('send')
            ->withConsecutive([])
            ->willReturn($response);


        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertLessThanOrEqual(time() + 3600, $token->getExpires());
        $this->assertGreaterThanOrEqual(time(), $token->getExpires());
        // TODO: Check returned id
        $this->assertNull($token->getResourceOwnerId(), 1);
    }

    // TODO: test other grants

}