<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Model\UserRepositoryInterface;
use App\Infrastructure\UserRepository;
use App\Utils\PhpTemplateEngine;
use App\Connection\ConnectionProvider;
use App\Exceptions\DataBaseException;
use App\Model\User;

// перименовал классы
// раскидал по папкам
// вытащил из функции ?
// починил аватарку ?


class UserController extends AbstractController
{
    const PNG = 'image/png';
    const JPEG = 'image/jpeg';
    const GIF = 'image/gif';
    const SAVE_DIR = "./uploads/";

    private UserRepositoryInterface $table;

    public function __construct()  // можно  попробовать передать Interface
    {
        $connectionParams = ConnectionProvider::getConnectionParams();
        $connection = ConnectionProvider::connectDatabase($connectionParams);
        $this->table = new UserRepository($connection);
    }
    public function index(): Response
    {
        return $this->redirectToRoute('add_user');
    }

    public function login(): Response
    {
        $content = PhpTemplateEngine::renderHTML('add_user_form.html');
        return new Response($content);
    }

    public function addNewUser(Request $request): void
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
        $last = $this->table->addUser($user);
        if ($last)
        {
            if ($request->files->get('avatar_path'))
            {
                $this->saveAvatar($request->files->get('avatar_path'), $last);
            }
            $redirectUrl = "/show_user?user_id={$last}";
            $this->redirectToPage($redirectUrl);
        }
    }

    public function showUser(Request $request): ?Response
    {
        try
        {
            $id = $request->get('user_id') ?? null;
            if ($id === null)
                throw new DataBaseException('Parameter user_id is not defined');
            $user = $this->table->findUser($id);
            if (!is_null($user))
            {
                $content = PhpTemplateEngine::renderPHP('user_page.php', $user);
                return new Response($content);
            }
            else
            {
                throw new DataBaseException("User not found");
            }
        }
        catch (DataBaseException $e)
        {
            echo $e->getMessage();
            return null;
        }
    }

    public function updateUser(Request $request): ?Response
    {
        try {
            $id = $request->get('user_id') ?? null;
            if ($id === null)
                throw new DataBaseException('Parameter userId is not defined');

            $user = $this->table->findUser($id);
            if (!is_null($user))
            {
                $this->changeUserData($user, $request);
                $this->table->updateUser($user);

                // не работает отображение
                if ($request->files->get('avatar_path'))
                {
                    var_dump($request->files->get('avatar_path'));

                    echo "<br>";
                    $this->saveAvatar($request->files->get('avatar_path'), $id);
                }
                $content = PhpTemplateEngine::renderPHP('user_page.php', $user);
                return new Response($content);
            }
            else
            {
                throw new DataBaseException("User not found");
            }
        }
        catch (DataBaseException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function deleteUser(Request $request): Response
    {
        $id = $request->get('user_id') ?? null;
        if ($id === null)
            throw new DataBaseException('Parameter userId is not defined');
        $this->table->deleteUser($id);

        $content = PhpTemplateEngine::renderHTML('delete_status.html');
        return new Response($content);
    }

    private function redirectToPage(string $redirectUrl): void
    {
        header('Location: ' . $redirectUrl, true, 303);
        die();
    }

    private function saveAvatar(UploadedFile $avatar, int $id): void
    {
        if ($avatar->isValid()) {
            $type = $avatar->getClientMimeType();
            $fileName = "avatar" . "{$id}" . "." . $avatar->getClientOriginalExtension();

            echo "<br>";
            var_dump($avatar);

            if ($type === self::PNG ||
                $type === self::JPEG ||
                $type === self::GIF)
            {
                $avatarPath = self::SAVE_DIR . $fileName;
                /*if (move_uploaded_file($avatar->getPathname(), $avatarPath))
                    echo "Ok";
                else
                    echo "NOT OK";*/
                $avatar->move(self::SAVE_DIR, $fileName);
                if (file_exists($avatarPath))
                {
                    echo "Ok";
                }
                else
                {
                    echo "Not OK";
                }
                echo "<br>" . $avatar->getPathname();
                echo "<br>" . $avatar->getPath();
                echo "<br>" . $avatarPath;

                $this->table->saveAvatarPathToDB($avatarPath, $id);  // вытянуть из этой функции наружу
            }
            else
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
        $user->setAvatarPath(null);
    }
}