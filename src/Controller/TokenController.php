<?php
declare(strict_types=1);

namespace App\Controller;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends AbstractController
{
    public function __construct(
        private AuthorizationServer $server,
        private HttpFoundationFactoryInterface $httpFoundationFactory,
        private HttpMessageFactoryInterface $httpMessageFactory,
        private ResponseFactoryInterface $responseFactory,
    )
    {
    }

    #[Route('/oauth2/token', name: 'oauth2_token', methods: 'POST')]
    public function index(Request $request): Response
    {
        $psrRequest = $this->httpMessageFactory->createRequest($request);
        $psrResponse = $this->responseFactory->createResponse();

        try {

            $response = $this->server->respondToAccessTokenRequest($psrRequest, $psrResponse);

        } catch (OAuthServerException $exception) {

            // TODO: logging

            $response = $exception->generateHttpResponse($psrResponse);
        }

        // TODO: logging

        return $this->httpFoundationFactory->createResponse($response);
    }
}