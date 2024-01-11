<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = (new Email())
                ->from(new Address('bvsmail@ukr.net', 'mvsmail'))
                ->to('bvsmail@ukr.net')
                ->subject($form->get('subject')->getData())
                ->text($form->get('email')->getData().' '.$form->get('message')->getData())
                ->html($form->get('email')->getData().' '.$form->get('message')->getData());

            $mailer->send($email);
            $this->addFlash('success', 'Your message was sent!');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form
        ]);
    }
}
