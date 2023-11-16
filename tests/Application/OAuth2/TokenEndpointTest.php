<?php

declare(strict_types=1);

namespace App\Tests\Application\OAuth2;

use App\Tests\Fixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TokenEndpointTest extends WebTestCase
{

    public function testSuccessfulClientCredentialsRequest(): void
    {
        $client = static::createClient();
        
        $accessToken = null;

        $client->request('POST', '/token', [
            'client_id' => AppFixtures::PRIVATE_CLIENT_IDENTIFIER,
            'client_secret' => AppFixtures::PRIVATE_CLIENT_SECRET,
            'grant_type' => 'client_credentials',
        ]);

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/json; charset=UTF-8', $response->headers->get('Content-Type'));

        $jsonResponse = json_decode($response->getContent(), true);

        $this->assertSame('Bearer', $jsonResponse['token_type']);
        $this->assertLessThanOrEqual(3600, $jsonResponse['expires_in']);
        $this->assertGreaterThan(0, $jsonResponse['expires_in']);
        $this->assertNotEmpty($jsonResponse['access_token']);
        $this->assertArrayNotHasKey('refresh_token', $jsonResponse);
        $this->assertSame('bar', $response->headers->get('foo'));

        $this->assertSame('foo', $accessToken->getClient()->getIdentifier());
        $this->assertNull($accessToken->getUserIdentifier());
    }

//    public function testSuccessfulPasswordRequest(): void
//    {
//        $client = static::createClient();
//
//        $accessToken = null;
//        $refreshToken = null;
//
//        $client->request('POST', '/token', [
//            'client_id' => 'foo',
//            'client_secret' => 'secret',
//            'grant_type' => 'password',
//            'username' => 'user',
//            'password' => 'pass',
//        ]);
//
//        $response = $client->getResponse();
//
//        $this->assertSame(200, $response->getStatusCode());
//        $this->assertSame('application/json; charset=UTF-8', $response->headers->get('Content-Type'));
//
//        $jsonResponse = json_decode($response->getContent(), true);
//
//        $this->assertSame('Bearer', $jsonResponse['token_type']);
//        $this->assertLessThanOrEqual(3600, $jsonResponse['expires_in']);
//        $this->assertGreaterThan(0, $jsonResponse['expires_in']);
//        $this->assertNotEmpty($jsonResponse['access_token']);
//        $this->assertNotEmpty($jsonResponse['refresh_token']);
//        $this->assertSame($response->headers->get('foo'), 'bar');
//
//        $this->assertSame('foo', $accessToken->getClient()->getIdentifier());
//        $this->assertSame('user', $accessToken->getUserIdentifier());
//        $this->assertSame($accessToken->getIdentifier(), $refreshToken->getAccessToken()->getIdentifier());
//    }

//    public function testSuccessfulRefreshTokenRequest(): void
//    {
//        $client = static::createClient();
//
//        $refreshToken = $client
//            ->getContainer()
//            ->get(RefreshTokenManagerInterface::class)
//            ->find(FixtureFactory::FIXTURE_REFRESH_TOKEN);
//
//        $accessToken = null;
//        $refreshTokenEntity = null;
//
//        $client->request('POST', '/token', [
//            'client_id' => 'foo',
//            'client_secret' => 'secret',
//            'grant_type' => 'refresh_token',
//            'refresh_token' => TestHelper::generateEncryptedPayload($refreshToken),
//        ]);
//
//        $response = $client->getResponse();
//
//        $this->assertSame(200, $response->getStatusCode());
//        $this->assertSame('application/json; charset=UTF-8', $response->headers->get('Content-Type'));
//
//        $jsonResponse = json_decode($response->getContent(), true);
//
//        $this->assertSame('Bearer', $jsonResponse['token_type']);
//        $this->assertLessThanOrEqual(3600, $jsonResponse['expires_in']);
//        $this->assertGreaterThan(0, $jsonResponse['expires_in']);
//        $this->assertNotEmpty($jsonResponse['access_token']);
//        $this->assertNotEmpty($jsonResponse['refresh_token']);
//        $this->assertFalse($response->headers->has('foo'));
//        $this->assertSame($response->headers->get('baz'), 'qux');
//
//        $this->assertSame($refreshToken->getAccessToken()->getClient()->getIdentifier(), $accessToken->getClient()->getIdentifier());
//        $this->assertSame($accessToken->getIdentifier(), $refreshTokenEntity->getAccessToken()->getIdentifier());
//    }
//
//    public function testSuccessfulAuthorizationCodeRequest(): void
//    {
//        $authCode = $client
//            ->getContainer()
//            ->get(AuthorizationCodeManagerInterface::class)
//            ->find(FixtureFactory::FIXTURE_AUTH_CODE);
//
//        $client->request('POST', '/token', [
//            'client_id' => 'foo',
//            'client_secret' => 'secret',
//            'grant_type' => 'authorization_code',
//            'redirect_uri' => 'https://example.org/oauth2/redirect-uri',
//            'code' => TestHelper::generateEncryptedAuthCodePayload($authCode),
//        ]);
//
//        $client
//            ->getContainer()
//            ->get('event_dispatcher')
//            ->addListener(OAuth2Events::TOKEN_REQUEST_RESOLVE, static function (TokenRequestResolveEvent $event): void {
//                $event->getResponse()->headers->set('foo', 'bar');
//            });
//
//        $response = $client->getResponse();
//
//        $this->assertSame(200, $response->getStatusCode());
//        $this->assertSame('application/json; charset=UTF-8', $response->headers->get('Content-Type'));
//
//        $jsonResponse = json_decode($response->getContent(), true);
//
//        $this->assertSame('Bearer', $jsonResponse['token_type']);
//        $this->assertLessThanOrEqual(3600, $jsonResponse['expires_in']);
//        $this->assertGreaterThan(0, $jsonResponse['expires_in']);
//        $this->assertNotEmpty($jsonResponse['access_token']);
//        $this->assertEmpty($response->headers->get('foo'), 'bar');
//    }
//
//    public function testSuccessfulAuthorizationCodeRequestWithPublicClient(): void
//    {
//        $authCode = $client
//            ->getContainer()
//            ->get(AuthorizationCodeManagerInterface::class)
//            ->find(FixtureFactory::FIXTURE_AUTH_CODE_PUBLIC_CLIENT);
//
//        $eventDispatcher = $client->getContainer()->get('event_dispatcher');
//
//        $eventDispatcher->addListener(OAuth2Events::TOKEN_REQUEST_RESOLVE, static function (TokenRequestResolveEvent $event): void {
//            $event->getResponse()->headers->set('foo', 'bar');
//        });
//
//        $wasRequestAccessTokenEventDispatched = false;
//        $wasRequestRefreshTokenEventDispatched = false;
//        $accessToken = null;
//        $refreshToken = null;
//
//        $eventDispatcher->addListener(RequestEvent::ACCESS_TOKEN_ISSUED, static function (RequestAccessTokenEvent $event) use (&$wasRequestAccessTokenEventDispatched, &$accessToken): void {
//            $wasRequestAccessTokenEventDispatched = true;
//            $accessToken = $event->getAccessToken();
//        });
//
//        $eventDispatcher->addListener(RequestEvent::REFRESH_TOKEN_ISSUED, static function (RequestRefreshTokenEvent $event) use (&$wasRequestRefreshTokenEventDispatched, &$refreshToken): void {
//            $wasRequestRefreshTokenEventDispatched = true;
//            $refreshToken = $event->getRefreshToken();
//        });
//
//        $client->request('POST', '/token', [
//            'client_id' => FixtureFactory::FIXTURE_PUBLIC_CLIENT,
//            'grant_type' => 'authorization_code',
//            'redirect_uri' => FixtureFactory::FIXTURE_PUBLIC_CLIENT_REDIRECT_URI,
//            'code' => TestHelper::generateEncryptedAuthCodePayload($authCode),
//        ]);
//
//        $response = $client->getResponse();
//
//        $this->assertSame(200, $response->getStatusCode());
//        $this->assertSame('application/json; charset=UTF-8', $response->headers->get('Content-Type'));
//
//        $jsonResponse = json_decode($response->getContent(), true);
//
//        $this->assertSame('Bearer', $jsonResponse['token_type']);
//        $this->assertLessThanOrEqual(3600, $jsonResponse['expires_in']);
//        $this->assertGreaterThan(0, $jsonResponse['expires_in']);
//        $this->assertNotEmpty($jsonResponse['access_token']);
//        $this->assertNotEmpty($jsonResponse['refresh_token']);
//        $this->assertSame($response->headers->get('foo'), 'bar');
//
//        $this->assertTrue($wasRequestAccessTokenEventDispatched);
//        $this->assertTrue($wasRequestRefreshTokenEventDispatched);
//
//        $this->assertSame($authCode->getClient()->getIdentifier(), $accessToken->getClient()->getIdentifier());
//        $this->assertSame($authCode->getUserIdentifier(), $accessToken->getUserIdentifier());
//        $this->assertSame($accessToken->getIdentifier(), $refreshToken->getAccessToken()->getIdentifier());
//    }
//

    public function testFailedTokenRequest(): void
    {
        $client = static::createClient();

        $param = $client->getContainer()->getParameter('private_key');

        $client->request('POST', '/token');
        $response = $client->getResponse();

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));

        $jsonResponse = json_decode($response->getContent(), true);

        $this->assertSame('unsupported_grant_type', $jsonResponse['error']);
        $this->assertSame('The authorization grant type is not supported by the authorization server.', $jsonResponse['message']);
        $this->assertSame('Check that all required parameters have been provided', $jsonResponse['hint']);
    }

//
//    public function testFailedClientCredentialsTokenRequest(): void
//    {
//        $eventDispatcher = $client->getContainer()->get('event_dispatcher');
//
//        $eventDispatcher->addListener(OAuth2Events::TOKEN_REQUEST_RESOLVE, static function (TokenRequestResolveEvent $event): void {
//            $event->getResponse()->headers->set('foo', 'bar');
//        });
//
//        $wasClientAuthenticationEventDispatched = false;
//
//        $eventDispatcher->addListener(RequestEvent::CLIENT_AUTHENTICATION_FAILED, static function (RequestEvent $event) use (&$wasClientAuthenticationEventDispatched, &$accessToken): void {
//            $wasClientAuthenticationEventDispatched = true;
//        });
//
//        $client->request('POST', '/token', [
//            'client_id' => 'foo',
//            'client_secret' => 'wrong',
//            'grant_type' => 'client_credentials',
//        ]);
//
//        $response = $client->getResponse();
//
//        $this->assertSame(401, $response->getStatusCode());
//        $this->assertSame('application/json', $response->headers->get('Content-Type'));
//
//        $jsonResponse = json_decode($response->getContent(), true);
//
//        $this->assertSame('invalid_client', $jsonResponse['error']);
//        $this->assertSame('Client authentication failed', $jsonResponse['message']);
//        $this->assertSame('bar', $response->headers->get('foo'));
//
//        $this->assertTrue($wasClientAuthenticationEventDispatched);
//    }
}
