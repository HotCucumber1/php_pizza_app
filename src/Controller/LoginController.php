<?php

namespace App\Controller;

use App\Service\UserServiceInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController
{
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

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

    public function signIn(Request $request): Response
    {
        $email = $request->get("email");
        $password = $request->get("password");

        $user = $this->userService->getUserByEmail($email);

        if (!$this->userService->isPasswordRight($user, $password))
        {
            throw new BadRequestException("Password is incorrect");
        }
        if (!$this->userService->isAdmin($user))
        {
            throw new BadRequestException("Role is incorrect");
        }
        return $this->redirectToRoute("show_main_page", [
            'id' => $user->getUserId()
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