<?php

namespace App\Controller;

use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserController extends AbstractController
{
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    public function addNewUser(Request $request): Response
    {

        $lastUserId = $this->userService->saveUser($request->get('name'),
                                                    $request->get('last_name'),
                                                    $request->get('email'),
                                                    ($request->get('phone') == '') ? null : $request->get('phone'),
                                                    null,
                                                    $request->get('password'));

        if ($request->files->get('avatar_path'))
        {
            $this->userService->updateAvatar($request->files->get('avatar_path'), (string)$lastUserId);
        }

        SessionController::putUserIdInSession($lastUserId);
        return $this->redirectToRoute('show_main_page');
    }
}
