<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: ['/'], name: 'home')]
    public function lSoginOrRexcipes(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('recipe');
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route(path: ['/login'], name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('recipe');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: ['/resetPassword'], name: 'app_reset_Password', methods: ['GET', 'POST'])]
    public function resetPassword(Request $request): Response
    { {
            $formData = [
                'email' => '',
            ];

            $form = $this->createFormBuilder($formData)->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                

                return $this->redirectToRoute('login');
            }

            return $this->render('security/resetPassword.html.twig');
        }
    }
}
