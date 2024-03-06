<?php

namespace App\Controller;

use App\Service\LoginWith\LoginWithInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, array $loginWithInterfaces): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

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
                'externalLoginsData' => $loginWithInterfaces,
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
