<?php

namespace App\Infrastructure;

use App\Connection\ConnectionProvider;
use App\Model\User;
use App\Model\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class UserRepository implements UserRepositoryInterface
{
    private \PDO $connection;

    public function __construct()
    {
        $connectionParams = ConnectionProvider::getConnectionParams();
        $this->connection = ConnectionProvider::connectDatabase($connectionParams);
    }

    public function addUser(User $user): int
    {
        $query = 'INSERT INTO 
                `user` (
                        `first_name`, 
                        `last_name`, 
                        `middle_name`, 
                        `gender`, 
                        `birth_date`, 
                        `email`, 
                        `phone`, 
                        `avatar_path`)
              VALUES (
                      :first_name, 
                      :last_name, 
                      :middle_name, 
                      :gender, 
                      :birth_date, 
                      :email, 
                      :phone, 
                      :avatar_path);';
        $request = $this->connection->prepare($query);

        $request->execute([
            ':first_name' => $user->getFirstName(),
            ':last_name' => $user->getLastName(),
            ':middle_name' => $user->getMiddleName(),
            ':gender' => $user->getGender(),
            ':birth_date' => $user->getBirthDate(),
            ':email' => $user->getEmail(),
            ':phone' => $user->getPhone(),
            ':avatar_path' => $user->getAvatarPath(),
        ]);
        return (int)$this->connection->lastInsertId();
    }

    public function findUser(int $userId): ?User
    {
        $query = "SELECT 
                    `user_id`,
                    `first_name`, 
                    `last_name`, 
                    `middle_name`, 
                    `gender`, 
                    `birth_date`, 
                    `email`, 
                    `phone`, 
                    `avatar_path`
                  FROM 
                      `user`
                  WHERE 
                      `user_id` = :user_id;";
        $request = $this->connection->prepare($query);
        $request->execute([
            ':user_id' => $userId
        ]);

        $userData = $request->fetch(\PDO::FETCH_ASSOC);
        if ($userData)
            return $this->createUser($userData);
        return null;
    }

    public function updateUser(User $user): void
    {
        if (is_null($this->findUser($user->getUserId())))
        {
            throw new BadRequestException("User does not exist");
        }
        $query = "UPDATE
                      user
                  SET
                      first_name = :first_name,
                      last_name = :last_name,
                      middle_name = :middle_name,
                      gender = :gender,
                      birth_date = :birth_date,
                      email = :email,
                      avatar_path = :avatar_path,
                      phone = :phone
                  WHERE
                      user_id = :user_id;";
        $request = $this->connection->prepare($query);
        $request->execute([
            ':first_name' => $user->getFirstName(),
            ':last_name' => $user->getLastName(),
            ':middle_name' => $user->getMiddleName(),
            ':gender' => $user->getGender(),
            ':birth_date' => $user->getBirthDate(),
            ':email' => $user->getEmail(),
            ':avatar_path' => $user->getAvatarPath(),
            ':phone' => $user->getPhone(),
            ':user_id' => $user->getUserId()
        ]);
    }

    public function deleteUser(int $id): void
    {
        $query = "DELETE FROM
                          user
                      WHERE
                          user_id = :user_id";
        $request = $this->connection->prepare($query);
        $request->execute([
            ':user_id' => $id
        ]);
    }

    public function saveAvatarPathToDB(User $user): void
    {
        $query = "UPDATE
                    user
                  SET
                    avatar_path = :avatar
                  WHERE
                    user_id = :id;";
        $request = $this->connection->prepare($query);
        $request->execute([
            ':avatar' => $user->getAvatarPath(),
            ':id' => $user->getUserId(),
        ]);
    }

    private function createUser(array $user): User
    {
        return new User(
            $user['user_id'],
            $user['first_name'],
            $user['last_name'],
            $user['middle_name'],
            $user['gender'],
            $user['birth_date'],
            $user['email'],
            $user['phone'],
            $user['avatar_path']
        );
    }
}
