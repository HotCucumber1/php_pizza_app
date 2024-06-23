<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    public function index(): Response
    {
        return $this->redirectToRoute('sign_in');

    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render("sign_in/sign_in.html.twig", [
            'error' => $error,
        ]);
    }

    public function signUp(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render("add_user/add_user.html.twig", [
            'error' => $error,
        ]);
    }

    public function logout(): Response
    {
        return $this->redirectToRoute('index');
    }
}