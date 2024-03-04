<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginWithGoogleController extends AbstractController
{
    #[Route('/login/with/google', name: 'app_login_with_google')]
    public function index(
        Request $request,
        UserRepository $userRepository,
        \Google\Client $googleClient
    ): Response
    {
        var_dump($request->query->all());
        var_dump($request->get('code'));
        if ($request->get('code')) {
            $googleClient->setClientId('386543107688-k4s2aek7cpf3nqbkqe1t27q08hhsr80e.apps.googleusercontent.com');
            $googleClient->setClientSecret('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');
            $googleClient->setRedirectUri('https://symfony.forum.org/login/with/google');

            $token = $googleClient->fetchAccessTokenWithAuthCode($request->get('code'));

            $oauth2 = new \Google\Service\Oauth2($googleClient);
            $googleClient->setAccessToken($token['access_token']);
            $googleAccountInfo = $oauth2->userinfo->get();
            $user = $userRepository->findOneByEmail($googleAccountInfo->email);

            var_dump($googleAccountInfo);
        }
       // var_dump($request->query->all());
        return $this->render('login_with_google/index.html.twig', [
            'controller_name' => 'LoginWithGoogleController',
        ]);
    }
}
