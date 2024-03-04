<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Google\Client;
use Google\Service\Oauth2;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class GoogleAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;
    public function __construct(
        private Client $googleClient,
        private UrlGeneratorInterface $urlGenerator,
        private UserProviderInterface $userProvider
    )  {

    }
    public function supports(Request $request): ?bool
    {
        return $request->get('code')
            && $request->get('scope')
            && $request->get('authuser') !== null
            && $request->get('prompt') !== null;
    }

    public function authenticate(Request $request): Passport
    {
       // $this->googleClient->setClientId('386543107688-k4s2aek7cpf3nqbkqe1t27q08hhsr80e.apps.googleusercontent.com');
        //$this->googleClient->setClientSecret('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');
       // $this->googleClient->setRedirectUri('https://symfony.forum.org/login/with/google');

        $token = $this->googleClient->fetchAccessTokenWithAuthCode($request->get('code'));
        if (null === $token || !isset($token['access_token']) ) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        $oauth2 = new Oauth2($this->googleClient);
        $this->googleClient->setAccessToken($token['access_token']);
        $googleAccountInfo = $oauth2->userinfo->get();
        $email = $googleAccountInfo->email;

        return new SelfValidatingPassport(new UserBadge($email,
            function () use ($email) {
                return $this->userProvider->loadUserByIdentifier($email);
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
}
