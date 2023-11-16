<?php
declare(strict_types=1);

namespace App\Controller;

use League\OAuth2\Server\AuthorizationServer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorizationController extends AbstractController
{
    #[Route('/oauth2/auth', name: 'oauth2_auth', methods: ['GET', 'POST'])]
    public function index(AuthorizationServer $server, Security $security): Response
    {

        // logout the user before proceed
        $security->logout(false);

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}