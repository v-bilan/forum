<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FacebookLoginController extends AbstractController
{
    #[Route('/facebook/login', name: 'app_facebook_login')]
    public function index(Request $request): Response
    {
        var_dump($request->query->all());
        $params = [
            'client_id' => '703154201970104',
            'client_secret' => 'a39aa302f9061cae820778f3a3e65fd6',
            'redirect_uri' => $this->generateUrl(
                route: 'app_facebook_login',
                referenceType: UrlGeneratorInterface::ABSOLUTE_URL
            ),
            'code' => $request->get('code')
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        var_dump($response);
        $response = json_decode($response, true);
        if (isset($response['access_token']) && !empty($response['access_token'])) {
            // Execute cURL request to retrieve the user info associated with the Facebook account
            $ch = curl_init();
           // https://graph.facebook.com/me/permissions?debug=all
            curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v19.0/me?fields=email,name');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
            $response = curl_exec($ch);
            curl_close($ch);
            $profile = json_decode($response, true);
            dd($profile);
        }

        return $this->render('facebook_login/index.html.twig', [
            'controller_name' => 'FacebookLoginController',
        ]);
    }
}
