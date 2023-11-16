<?php

namespace App\Tests\Api\OAuth2;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientCredentialsTest extends WebTestCase
{
//    public function testFailedTokenRequest(): void
//    {
//        $this->client->request('POST', '/token');
//
//        $response = $this->client->getResponse();
//
//        $this->assertSame(400, $response->getStatusCode());
//        $this->assertSame('application/json', $response->headers->get('Content-Type'));
//
//        $jsonResponse = json_decode($response->getContent(), true);
//
//        $this->assertSame('unsupported_grant_type', $jsonResponse['error']);
//        $this->assertSame('The authorization grant type is not supported by the authorization server.', $jsonResponse['message']);
//        $this->assertSame('Check that all required parameters have been provided', $jsonResponse['hint']);
//    }
}