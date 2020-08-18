<?php

namespace Oip\SocialStore\User\UseCase\Register\Request;

use Oip\SocialStore\User\Entity\User;
use CUser;
use Oip\SocialStore\User\Repository\UserRepositoryInterface;
use Oip\Util\Phone\PhoneNormalizer;

class Handler
{
    use PhoneNormalizer;

    /** @var UserRepositoryInterface $repository */
    private $repository;

    public function __construct(UserRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * @param Command $command
     * @return User
     */
    public function handle(Command $command): User {

        $this->validate($command);

        $inputPhone = $command->phone;
        $command->phone = $this->normalize($command->phone);

        if($this->isUserExistByEmail($command->email)) {
            throw new UserExistByEmailException("Пользователь с email {$command->email} уже существует.");
        }

        if($this->isUserExistByPhone($command->phone)) {
            throw new UserExistByPhoneException("Пользователь с номером $inputPhone уже существует.");
        }

        $bxUserId = $this->addBxUser($command);

        $insertedId = $this->repository->add($command, $bxUserId);

        $newUser = $this->repository->getById($insertedId);

        $code = $newUser->generateVerificationCode();

        $this->repository->addVerification($insertedId, $code);

        return $newUser;
    }

    /**
     * @param Command
     * @throws StoreUserRegisterException
     */
    public function validate(Command $command) {

        $validates = [
            "email"     => "Необходимо заполнить email.",
            "phone"     => "Необходимо заполнить телефон.",
            "password"  => "Необходимо заполнить пароль.",
            "confirmPassword"  => "Необходимо подтверждение пароля.",
            "name"      => "Необходимо указать имя.",
        ];

        $messages = "";
        foreach ($validates as $field => $message) {
            if(!$command->$field) {
                $messages .= ($messages) ? "<br/>" . $message : $message;
            }
        }

        if($messages) {
            throw new StoreUserRegisterException($messages);
        }
        elseif($command->password !== $command->confirmPassword) {
            throw new StoreUserRegisterException("Поля 'Пароль' и 'Подтверждение пароля' не совпадают.");
        }
    }

    /**
     * @param Command $command
     * @return int
     */
    private function addBxUser(Command $command) {
        $u = new CUser();

        $login = "sh_" . $command->phone;

        $bxUserId = (int)$u->Add([
            "NAME" => $command->name,
            "LAST_NAME" => $command->surname,
            "SECOND_NAME" => $command->patronymic,
            "EMAIL" => $command->email,
            "PERSONAL_PHONE" => $command->phone,
            "LOGIN" => $login,
            "PASSWORD" => $command->password,
            "CONFIRM_PASSWORD" => $command->password,
            "ACTIVE" => "Y"
        ]);

        if($bxUserId > 0) {
            return $bxUserId;
        }
        else {
            throw new StoreUserRegisterException("Ошибка при создании пользователя: "  . $u->LAST_ERROR);
        }
    }

    /**
     * @param string $email
     * @return boolean
     */
    private function isUserExistByEmail(string $email) {
        return (bool)(count($this->getBxUsersByFilter(["EMAIL" => $email])));
    }

    /**
     * @param string $phone
     * @return boolean
     */
    private function isUserExistByPhone(string $phone) {
        return (bool)(count($this->getBxUsersByFilter(["PERSONAL_PHONE" => $phone])));
    }

    /**
     * @param array $bxUserFilter
     * @return array
     */
    private function getBxUsersByFilter(array $bxUserFilter) {
        $users = [];

        $by = "";
        $order = "";
        $rs = CUser::GetList($by,$order, $bxUserFilter);

        while($user = $rs->GetNext()) {
            $users[] = $user;
        }

        return $users;
    }
}