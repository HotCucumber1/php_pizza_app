<?php

namespace App\Controller;

use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    public function index(): Response
    {
        return $this->redirectToRoute('add_user');
    }

    public function login(): Response
    {
        return $this->render("add_user/add_user.html.twig");
    }

    public function addNewUser(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $lastUserId = $this->userService->saveUser($request->get('name'),
                                                    $request->get('last_name'),
                                                    ($request->get('middle_name') == '') ? null : $request->get('middle_name'),
                                                    $request->get('gender'),
                                                    new \DateTime($request->get('birth_date')),
                                                    $request->get('email'),
                                                    ($request->get('phone')) ? null : $request->get('phone'),
                                                    null,
                                                    $request->get('password'),
                                                    $hasher);

        if ($request->files->get('avatar_path'))
        {
            $this->userService->updateAvatar($request->files->get('avatar_path'), (string)$lastUserId);
        }
        return $this->redirectToRoute('show_user', ['user_id' => $lastUserId]);
    }

    public function showUser(Request $request): Response
    {
        $userId = $request->get('user_id') ?? null;
        if (is_null($userId))
        {
            throw new BadRequestException('Parameter user_id is not defined');
        }
        $user = $this->userService->getUser($userId);

        return $this->render("show_user/user_page.html.twig", [
            "user" => $user
        ]);
    }

    public function updateUser(Request $request): ?Response
    {
        $userId = $request->get('user_id') ?? null;
        if (is_null($userId))
        {
            throw new BadRequestException('Parameter userId is not defined');
        }

        $user = $this->userService->updateUser($userId,
                                        $request->get('name'),
                                        $request->get('last_name'),
                                        ($request->get('middle_name') == '') ? null : $request->get('middle_name'),
                                        $request->get('gender'),
                                        new \DateTime($request->get('birth_date')),
                                        $request->get('email'),
                                        ($request->get('phone') == '') ? null : $request->get('phone'),
                                        $request->files->get('avatar_path'));

        return $this->render("show_user/user_page.html.twig", [
            "user" => $user
        ]);
    }

    public function deleteUser(Request $request): Response
    {
        $userId = $request->get('user_id') ?? null;
        if (is_null($userId))
        {
            throw new BadRequestException('Parameter userId is not defined');
        }
        $this->userService->deleteUser($userId);

        return $this->render("delete_user/delete_page.html.twig");
    }

    public function showAllUsers(Request $request): Response
    {
        $users = $this->userService->getListUsers();

        return $this->render("users_list/users_list.html.twig", [
            "users" => $users
        ]);
    }

    public function test(Request $request): Response
    {
        return $this->render("/main_page.html");
    }

    /*public function adminDashboard(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
    }*/
}
