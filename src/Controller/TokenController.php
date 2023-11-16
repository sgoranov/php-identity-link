<?php
declare(strict_types=1);

namespace App\Controller;

use League\OAuth2\Server\AuthorizationServer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends AbstractController
{
    #[Route('/token', name: 'oauth2_token')]
    public function index(AuthorizationServer $server): Response
    {

        var_dump($server);
        exit();


        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}