<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, \Google\Client $googleClient): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

       // $googleClient->setClientId('386543107688-k4s2aek7cpf3nqbkqe1t27q08hhsr80e.apps.googleusercontent.com');
       // $googleClient->setClientSecret('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');
       // $googleClient->setRedirectUri('https://symfony.forum.org/login/with/google');

        $googleClient->addScope("email");
        $googleClient->addScope("profile");


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $facebookUrl = 'https://www.facebook.com/v19.0/dialog/oauth?' . http_build_query([
                'client_id' => '1961253021000653',
                'redirect_uri' => $this->generateUrl(
                    route: 'app_facebook_login',
                    referenceType: UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'state' => 'wer,12345'
            ]);

        return $this->render('security/login.html.twig',
            [
                'google_login_url' => $googleClient->createAuthUrl(),
                'facebook_login_url'=> $facebookUrl,
                'last_username' => $lastUsername,
                'error' => $error
            ]
        );
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
