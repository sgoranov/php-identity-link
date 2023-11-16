<?php
declare(strict_types=1);

namespace App\Controller;

use League\OAuth2\Server\AuthorizationServer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthroizationController extends AbstractController
{
    #[Route('/authorize', name: 'auth_index')]
    public function index(AuthorizationServer $server): Response
    {

        var_dump($server);
        exit();


        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}