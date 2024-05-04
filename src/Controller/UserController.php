<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Model\UserRepositoryInterface;
use App\Model\User;
use App\Utils\PhpTemplateEngine;


class UserController extends AbstractController
{
    private const PNG = 'image/png';
    private const JPEG = 'image/jpeg';
    private const GIF = 'image/gif';
    private const SAVE_DIR = './uploads/';

    public function __construct(
        private readonly UserRepositoryInterface $repository,
        /*private string $saveDir,*/
    ) {}

    public function index(): Response
    {
        return $this->redirectToRoute('add_user');
    }

    public function login(): Response
    {
        $content = PhpTemplateEngine::renderHTML('add_user_form.html');
        return new Response($content);
    }

    public function addNewUser(Request $request): Response
    {
        $user = new User(
            null,
            $request->get('name'),
            $request->get('last_name'),
            ($request->get('middle_name') == '') ? null : $request->get('middle_name'),
            $request->get('gender'),
            $request->get('birth_date'),
            $request->get('email'),
            ($request->get('phone') == '') ? null : $request->get('phone'),
            null,
        );
        $lastUserId = $this->repository->addUser($user);

        if ($request->files->get('avatar_path'))
        {
            $avatarPath = $this->saveAvatar($request->files->get('avatar_path'), $lastUserId);

            $user = $this->repository->findUser($lastUserId);
            $user->setAvatarPath($avatarPath);
            $this->repository->saveAvatarPathToDB($user);
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
        $user = $this->repository->findUser($userId);
        if (!is_null($user))
        {
            $content = PhpTemplateEngine::renderPHP('user_page.php', $user);
            return new Response($content);
        }
        else
        {
            throw new BadRequestException("User not found");
        }
    }

    public function updateUser(Request $request): ?Response
    {
        $userId = $request->get('user_id') ?? null;
        if (is_null($userId))
        {
            throw new BadRequestException('Parameter userId is not defined');
        }

        $user = $this->repository->findUser($userId);
        if (is_null($user))
        {
            throw new BadRequestException("User not found");
        }

        $this->changeUserData($user, $request);
        if ($request->files->get('avatar_path'))
        {
            $avatarPath = $this->saveAvatar($request->files->get('avatar_path'), $userId);
            $user->setAvatarPath($avatarPath);
        }
        $this->repository->updateUser($user);

        $content = PhpTemplateEngine::renderPHP('user_page.php', $user);
        return new Response($content);
    }

    public function deleteUser(Request $request): Response
    {
        $userId = $request->get('user_id') ?? null;
        if (is_null($userId))
        {
            throw new BadRequestException('Parameter userId is not defined');
        }
        $this->repository->deleteUser($userId);

        $content = PhpTemplateEngine::renderHTML('delete_status.html');
        return new Response($content);
    }

    private function saveAvatar(UploadedFile $avatar, int $userId): ?string
    {
        if (!$avatar->isValid())
        {
            return null;
        }
        $type = $avatar->getClientMimeType();
        $fileName = "avatar" . "{$userId}" . "." . $avatar->getClientOriginalExtension();

        if ($type === self::PNG ||
            $type === self::JPEG ||
            $type === self::GIF)
        {
            $avatarPath = self::SAVE_DIR . $fileName;
            $avatar->move(self::SAVE_DIR, $fileName);
            return "." . $avatarPath;
        }
        else
        {
            throw new \TypeError("Wrong type of image");
        }
    }

    private function changeUserData(User $user, Request $data): void
    {
        $user->setFirstName($data->get('name'));
        $user->setLastName($data->get('last_name'));
        $user->setMiddleName(($data->get('middle_name') == '') ? null : $data->get('middle_name'));
        $user->setGender($data->get('gender'));
        $user->setBirthDate($data->get('birth_date'));
        $user->setEmail($data->get('email'));
        $user->setPhone(($data->get('phone') === '') ? null : $data->get('phone'));
    }
}
