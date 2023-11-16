<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'security_login', methods: ['GET', 'POST'])]
    public function login(Request $request): Response
    {
        $error = null;
        if ($request->hasSession()) {
            $session = $request->getSession();
            $error = $session->get(SecurityRequestAttributes::AUTHENTICATION_ERROR);
            $session->remove(SecurityRequestAttributes::AUTHENTICATION_ERROR);
        }

        return $this->render('login/login.html.twig', [
            'form' => $this->createForm(LoginType::class),
            'error' => $error,
        ]);
    }

    #[Route('/google-authenticate', name: 'security_google_authenticate', methods: ['GET', 'POST'])]
    public function googleAuthenticate(EntityManagerInterface $entityManager): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
