<?php

namespace App\Security;

use App\Form\Type\LoginType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\InteractiveAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class AuthAuthenticator extends AbstractAuthenticator
    implements AuthenticationEntryPointInterface, InteractiveAuthenticatorInterface
{

    public function __construct(
        private RouterInterface $router,
        private UrlGeneratorInterface $urlGenerator,
        private FormFactoryInterface $formFactory,
        private UserRepository $userRepository,
    )
    {
    }

    public function authenticate(Request $request): Passport
    {
        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw new AuthenticationException('Invalid username or password');
        }

        $data = $form->getData();
        $user = $this->userRepository->getUser($data['user_id'], $data['password']);
        if ($user === null) {
            throw new AuthenticationException('Invalid username or password');
        }

        // TODO: check for google authenticate

        return new SelfValidatingPassport(new UserBadge($user->getId()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $params = $request->getSession()->get('auth_request_params', []);

        return new RedirectResponse($this->router->generate('oauth2_auth', $params));
    }

    public function supports(Request $request): ?bool
    {
        $url = $request->getBaseUrl() . $request->getPathInfo();

        return $request->isMethod('POST')
            && ($this->router->generate('security_login') === $url
                || $this->router->generate('security_google_authenticate') === $url);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse($this->router->generate($request->attributes->get('_route')));
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $request->getSession()->set('auth_request_params', $request->query->all());

        return new RedirectResponse($this->urlGenerator->generate('security_login'));
    }

    public function isInteractive(): bool
    {
        return true;
    }
}