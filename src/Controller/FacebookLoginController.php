<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FacebookLoginController extends AbstractController
{
    #[Route('/facebook/login', name: 'app_facebook_login')]
    public function index(Request $request): Response
    {
        var_dump($request->query->all());
        return $this->render('facebook_login/index.html.twig', [
            'controller_name' => 'FacebookLoginController',
        ]);
    }
}
