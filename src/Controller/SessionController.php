<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class SessionController
{
    const SESSION_NAME = 'user';

    public static function putUserIdInSession(int $id): bool
    {
        $_SESSION['user_id'] = $id;
        $_SESSION['order'] = [];
        if ($_SESSION['user_id'])
        {
            return true;
        }
        return false;
    }

    public static function takeUserIdFromSession(): int
    {
        session_name(self::SESSION_NAME);
        session_start();
        if (in_array('user_id', array_keys($_SESSION)))
        {
            return $_SESSION['user_id'];
        }
        throw new UnauthorizedHttpException('You are not authorized');
    }

    public static function putPizzaInStorage(int $id): void
    {
        session_name(self::SESSION_NAME);
        session_start();

        if (isset($_SESSION['order'][$id]))
        {
            $_SESSION['order'][$id] += 1;
        }
        else
        {
            $_SESSION['order'][$id] = 1;
        }
    }

    public static function takePizzasFromStorage(): array
    {
        session_name(self::SESSION_NAME);
        session_start();
        return $_SESSION['order'];
    }

    public static function clearOrder(): void
    {
        session_name(self::SESSION_NAME);
        session_start();

        $_SESSION['order'] = [];
    }

    public static function destroySession(): void
    {
        session_name(self::SESSION_NAME);
        session_start();

        $_SESSION = [];
        session_destroy();
        setcookie(session_name(), "", time() - 3600);
    }


}