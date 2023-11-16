<?php
declare(strict_types=1);

namespace App\Tests\Application;

use App\Repository\UserRepository;
use App\Tests\Fixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class LoginControllerTest extends WebTestCase
{

    public function testSuccessfulLoginRequest(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get(RouterInterface::class);
        $security = $client->getContainer()->get(Security::class);
        $userRepository = $client->getContainer()->get(UserRepository::class);

        $client->request(
            'POST',
            $router->generate('security_login'),
            [
                'login' => [
                    'user_id' => AppFixtures::USER_IDENTIFIER,
                    'password' => AppFixtures::USER_PASSWORD,
                    'submit' => 'submit',
                ],
            ]
        );

        $response = $client->getResponse();

        list($user) = $userRepository->findBy(['username' => AppFixtures::USER_IDENTIFIER]);
        $this->assertSame($security->getUser()->getUserIdentifier(), $user->getId());

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame($response->headers->get('Location'), $router->generate('oauth2_auth'));
    }


    public function testBadCredentialsLoginRequest(): void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get(RouterInterface::class);

        $client->request(
            'POST',
            $router->generate('security_login'),
            [
                'login' => [
                    'user_id' => 'user',
                    'password' => 'pass',
                    'submit' => 'submit',
                ],
            ]
        );

        $response = $client->getResponse();

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame($response->headers->get('Location'), $router->generate('security_login'));

        $session = $client->getRequest()->getSession();
        $error = $session->get(SecurityRequestAttributes::AUTHENTICATION_ERROR)->getMessage();
        $this->assertSame($error, 'Invalid username or password');
    }
}