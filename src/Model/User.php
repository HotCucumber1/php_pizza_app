<?php
namespace App\Model;

class User
{
    private const DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function __construct(private ?int $userId,
                                private string $firstName,
                                private string $lastName,
                                private ?string $middleName,
                                private string $gender,
                                private ?string $birthDate,
                                /*private DateTimeImmutable $birthDate,*/
                                private string $email,
                                private ?string $phone,
                                private ?string $avatar_path)
    {
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @return string|null
     */
    public function getBirthDate(): ?string
    {
        return empty($this->birthDate) ? null : $this->birthDate;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return string|null
     */
    public function getAvatarPath(): ?string
    {
        return $this->avatar_path;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param string|null $middleName
     */
    public function setMiddleName(?string $middleName): void
    {
        $this->middleName = $middleName;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string|null $birthDate
     */
    public function setBirthDate(?string $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @param string|null $avatar_path
     */
    public function setAvatarPath(?string $avatar_path): void
    {
        $this->avatar_path = $avatar_path;
    }
}