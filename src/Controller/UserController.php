<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Repository\UserRepository;
use App\Entity\User;


class UserController extends AbstractController
{
    private const PNG = 'image/png';
    private const JPEG = 'image/jpeg';
    private const GIF = 'image/gif';
    private const SAVE_DIR = './uploads/';

    public function __construct(
        private readonly UserRepository $repository
    )
    {}

    public function index(): Response
    {
        return $this->redirectToRoute('add_user');
    }

    public function login(): Response
    {
        return $this->render("add_user/add_user.html.twig");
    }

    public function addNewUser(Request $request): Response
    {
        try
        {
            $user = new User(
                null,
                $request->get('name'),
                $request->get('last_name'),
                ($request->get('middle_name') == '') ? null : $request->get('middle_name'),
                $request->get('gender'),
                new \DateTime($request->get('birth_date')),
                $request->get('email'),
                ($request->get('phone') == '') ? null : $request->get('phone'),
                null,
            );
            $lastUserId = $this->repository->store($user);

            if ($request->files->get('avatar_path'))
            {
                $avatarPath = $this->saveAvatar($request->files->get('avatar_path'), $lastUserId);

                $user = $this->repository->findUserById($lastUserId);
                $user->setAvatarPath($avatarPath);
                $this->repository->store($user);
            }
            return $this->redirectToRoute('show_user', ['user_id' => $lastUserId]);
        }
        catch (\Exception $exc)
        {
            throw new BadRequestException("ERROR: " . $exc->getMessage(), 404);
        }
    }

    public function showUser(Request $request): Response
    {
        $userId = $request->get('user_id') ?? null;
        if (is_null($userId))
        {
            throw new BadRequestException('Parameter user_id is not defined');
        }
        $user = $this->repository->findUserById($userId);
        if (is_null($user))
        {
            throw new BadRequestException("User not found");
        }
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

        $user = $this->repository->findUserById($userId);
        if (is_null($user))
        {
            throw new BadRequestException("User not found");
        }

        $this->updateUserData($user, $request);
        if ($request->files->get('avatar_path'))
        {
            $avatarPath = $this->saveAvatar($request->files->get('avatar_path'), $userId);
            $user->setAvatarPath($avatarPath);
        }
        $this->repository->store($user);
        return $this->render("show_user/user_page.html.twig", [
            "user" => $user
        ]);
    }

    public function deleteUser(Request $request): Response
    {
        $userId = $request->get('user_id') ?? null;
        $user = $this->repository->findUserById($userId);
        if (is_null($userId))
        {
            throw new BadRequestException('Parameter userId is not defined');
        }
        $this->repository->delete($user);

        return $this->render("delete_user/delete_page.html.twig");
    }

    public function showAllUsers(Request $request): Response
    {
        $users = $this->repository->findUsersList();
        if (!$users)
        {
            throw new BadRequestException('Users not found');
        }
        return $this->render("users_list/users_list.html.twig", [
            "users" => $users
        ]);
    }

    private function saveAvatar(UploadedFile $avatar, int $userId): ?string
    {
        if (!$avatar->isValid())
        {
            return null;
        }
        $type = $avatar->getClientMimeType();
        $file = "avatar" . "{$userId}" . "." . $avatar->getClientOriginalExtension();

        if ($type === self::PNG ||
            $type === self::JPEG ||
            $type === self::GIF)
        {
            $avatarPath = self::SAVE_DIR . $file;
            $avatar->move(self::SAVE_DIR, $file);
            return "." . $avatarPath;
        }
        else
        {
            throw new \TypeError("Wrong type of image");
        }
    }

    private function updateUserData(User $user, Request $data): void
    {
        $user->setFirstName($data->get('name'));
        $user->setLastName($data->get('last_name'));
        $user->setMiddleName(($data->get('middle_name') == '') ? null : $data->get('middle_name'));
        $user->setGender($data->get('gender'));
        $user->setBirthDate(new \DateTime($data->get('birth_date')));
        $user->setEmail($data->get('email'));
        $user->setPhone(($data->get('phone') === '') ? null : $data->get('phone'));
    }

}
