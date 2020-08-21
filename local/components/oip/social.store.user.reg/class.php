<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;
use Oip\SocialStore\User\Entity\Exception\AlreadyVerifiedException;
use Oip\SocialStore\User\Entity\Exception\IncorrectVerificationProcessException;
use Oip\SocialStore\User\Entity\Exception\VerificationCodeExpiredException;
use Oip\SocialStore\User\Entity\Exception\VerificationFailedException;
use Oip\SocialStore\User\UseCase\Register\Request\Command as RequestCommand;
use Oip\SocialStore\User\UseCase\Register\Confirm\Command as ConfirmCommand;
use Oip\SocialStore\User\UseCase\Register\Request\Handler as RequestHandler;
use Oip\SocialStore\User\UseCase\Register\Confirm\Handler as ConfirmHandler;
use Oip\SocialStore\User\Repository\UserRepository;
use Oip\SocialStore\User\UseCase\Register\Request\StoreUserRegisterException;
use Oip\SocialStore\User\UseCase\Register\Request\UserExistByPhoneException;
use Oip\SocialStore\User\UseCase\Register\Request\UserExistByEmailException;
use Bitrix\Main\Db\SqlQueryException;
use Oip\SocialStore\User\Entity\User;
use Bitrix\Main\Event;
use Bitrix\Main\HttpRequest;
use Oip\Util\Bitrix\DateTimeConverter;
use Oip\SocialStore\User\Repository;
use Bitrix\Main\SystemException;
use Oip\Util\Phone\PhoneNormalizer;
use Oip\Util\Phone\PhoneNormalizerException;

CBitrixComponent::includeComponentClass("oip:component");

class CSocialStoreUserReg extends COipComponent {

    use PhoneNormalizer;

    protected function initParams($arParams)
    {
        $arParams = parent::initParams($arParams);

        $this->setDefaultParam($arParams["AUTH_LINK"], "/");
        $this->setDefaultParam($arParams["RESTORE_LINK"], "/");
        $this->setDefaultParam($arParams["BACK_URL"], "/");

        return $arParams;
    }

    public function executeComponent()
    {
        global $USER;

        $exceptionLog = [];

        if ($USER->IsAuthorized()) {
            $this->arResult["IS_AUTH"] = 1;

            $this->includeComponentTemplate();
        }
        else {

            try {
                $request = Application::getInstance()->getContext()->getRequest();
                $exceptionLog = $this->handleAction($request);
            }
            catch(SystemException $exception) {
                $exceptionLog = [
                    "message" => $exception->getMessage(),
                    "stackTrace" => $exception->getTraceAsString(),
                    "request" => $request
                ];

                $this->arResult["EXCEPTIONS"][] = "Внутреняя ошибка сервера при попытке регистрации. "
                    ."<br>Пожалуйста, обратитесь в техподдержку или попробуйте повторить действие позже";
            }

        }

        if(!empty($exceptionLog)) {
            $this->throwCreateStoreUserErrorsEvent($exceptionLog);
        }

        return $exceptionLog;
    }

    /**
     * @param HttpRequest $request
     * @return string $action
     */
    private function parseAction(HttpRequest $request) {

        if($request->getPost("reg-request-action")) {
            return "reg-request-action";
        }
        elseif($request->getPost("reg-confirm-action")) {
            return "reg-confirm-action";
        }
        elseif($request->getPost("restore-confirm-phone-action")) {
            return "restore-confirm-phone-action";
        }

        elseif($request->get("reg-confirm-form")) {
            return "reg-confirm-form";
        }

        elseif($request->get("restore-confirm-phone")) {
            return "restore-confirm-phone";
        }

        return "";
    }

    private function handleAction(HttpRequest $request) {
        $exceptionLog = [];

        $action = $this->parseAction($request);

        switch($action) {
            case "reg-request-action":
                $exceptionLog = $this->regRequestAction($request);
            break;

            case "reg-confirm-action":
                $exceptionLog = $this->regConfirmAction($request);
            break;

            case "restore-confirm-phone-action":
                $exceptionLog = $this->restoreConfirmAction($request);
            break;

            case "reg-confirm-form":
                $exceptionLog = $this->regConfirmForm($request);
            break;

            case "restore-confirm-phone":
                $this->includeComponentTemplate("restore-confirm");
            break;

            default:
                $this->includeComponentTemplate();
            break;
        }

        return $exceptionLog;
    }

    private function regRequestAction(HttpRequest $request) {

        $exceptionLog = [];

        $regCommand = new RequestCommand;
        $regCommand->email = $request->getPost("store-user-reg-email");
        $regCommand->phone = $request->getPost("store-user-reg-phone");
        $regCommand->password = $request->getPost("store-user-reg-password");
        $regCommand->confirmPassword = $request->getPost("store-user-reg-confirm-password");
        $regCommand->name = $request->getPost("store-user-reg-name");
        $regCommand->surname = $request->getPost("store-user-reg-surname");
        $regCommand->patronymic = $request->getPost("store-user-reg-patronymic");

        $repository = new UserRepository(Application::getConnection(), new DateTimeConverter());
        $handler = new RequestHandler($repository);

        try {
            $newStoreUser = $handler->handle($regCommand);

            global $APPLICATION;
            LocalRedirect($APPLICATION->GetCurDir() .
                "?reg-confirm-form=yes&user={$newStoreUser->getPhone()}&back_url={$this->arParams["BACK_URL"]}");
        }

        catch (UserExistByEmailException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
            ];

            $this->arResult["ERRORS"][] =  $exception->getMessage()
                . "<br>Если это вы, воспользуйтесь этой "
                ."<a href='{$this->arParams['RESTORE_LINK']}?forgot_password=yes&email={$regCommand->email}"
                ."&back_url={$this->arParams['BACK_URL']}'>ссылкой</a> для восстановления доступа.";

            $this->arResult["DANGER_CSS"]["email"] = "uk-form-danger";
        }
        catch (UserExistByPhoneException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
            ];

            $this->arResult["ERRORS"][] =  $exception->getMessage()
                . "<br>Если это вы, воспользуйтесь этой "
                ."<a href='{$this->arParams['RESTORE_LINK']}?forgot_password=yes&phone={$regCommand->phone}"
                ."&back_url={$this->arParams['BACK_URL']}'>ссылкой</a> для восстановления доступа.";

            $this->arResult["DANGER_CSS"]["phone"] = "uk-form-danger";
        }

        catch (PhoneNormalizerException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
            ];

            $this->arResult["ERRORS"][] =  $exception;

            $this->arResult["DANGER_CSS"]["phone"] = "uk-form-danger";
        }

        catch (StoreUserRegisterException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
            ];

            $this->arResult["ERRORS"][] = $exception;

            $this->setDangerCss($regCommand);
        }

        $this->includeComponentTemplate();

        return $exceptionLog;
    }

    private function regConfirmAction(HttpRequest $request) {

        $exceptionLog = [];

        $command = new ConfirmCommand;

        $command->verificationCode = $request->getPost("store-user-reg-confirm-code");
        $command->userPhone = (int)$request->getPost("store-user-reg-confirm-user");

        $user = null;

        $repository = new UserRepository(Application::getConnection(), new DateTimeConverter());
        $handler = new ConfirmHandler($repository);

        try {
            $user = $repository->getByPhone($command->userPhone);
            $handler->handle($command);

            global $USER;
            if($USER->Authorize($user->getBxId())) {
                LocalRedirect($this->arParams["BACK_URL"]);
            }
            else {
                $this->arResult["EXCEPTIONS"][] = "Вы успешно зарегистрировались, "
                    ."но произошла внутреняя ошибка сервера при попытке аутентификации. "
                    ."Вы можете воспользоваться номером телефона или почтой для входа в магазин,"
                    ." пройдя по этой <a href='{$this->arParams['AUTH_LINK']}'>ссылке</a>";

                $this->includeComponentTemplate();
            }
        }
        catch (Repository\NotFoundException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
                "request" => $request
            ];

            $this->arResult["EXCEPTIONS"][] =  "Пользователь не найден." .
                "<br>Пожалуйста, повторите процедуру региcтрации, либо обратитесь в техподдержку.";

            $this->includeComponentTemplate();
        }
        catch (VerificationFailedException $exception) {
            $this->arResult["ERRORS"][] = $exception;
            $this->arResult["USER_PHONE"] = $user->getPhone();

            $this->includeComponentTemplate("confirm");
        }
        catch (VerificationCodeExpiredException $exception) {
            $this->arResult["ERRORS"][] = $exception;
            $this->arResult["USER_PHONE"] = $user->getPhone();

            $this->includeComponentTemplate("confirm");
        }
        catch (SqlQueryException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
                "request" => $request
            ];

            $this->arResult["EXCEPTIONS"][] = "Внутреняя ошибка сервера при попытке регистрации. "
                ."<br>Пожалуйста, обратитесь в техподдержку или попробуйте повторить действие позже";
        }
        catch (Exception $e) {
            $this->arResult["EXCEPTIONS"][] = "Внутреняя ошибка сервера при попытке регистрации. "
                ."<br>Пожалуйста, обратитесь в техподдержку или попробуйте повторить действие позже";
        }

        return $exceptionLog;
    }

    private function regConfirmForm(HttpRequest $request) {

        $exceptionLog = [];

        $userPhone = $request->get("user");

        try {
            $repository = new UserRepository(Application::getConnection(), new DateTimeConverter());
            $verifyingUser = $repository->getByPhone($userPhone);
            $codeResend = $request->get("code-resend");

            $verifyingUser->checkUserPhoneVerified();
            $verifyingUser->checkVerificationStatus();

            if($codeResend) {
                $newCode = $verifyingUser->generateVerificationCode();
                $repository->addVerification($verifyingUser->getId(), $newCode);
                $verifyingUser = $repository->getByPhone($userPhone);
            }

            $this->throwCreateStoreUserEvent($verifyingUser);

            $this->arResult["USER_PHONE"] = $verifyingUser->getPhone();

            $this->includeComponentTemplate("confirm");
        }
        catch(AlreadyVerifiedException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
            ];

            $this->arResult["EXCEPTIONS"][] =  $exception->getMessage() .
                "<br>Пожалуйста, повторите процедуру региcтрации, либо обратитесь в техподдержку.";

            $this->includeComponentTemplate();

        }
        catch (IncorrectVerificationProcessException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
            ];

            $this->arResult["EXCEPTIONS"][] =  $exception->getMessage() .
                "<br>Пожалуйста, повторите процедуру региcтрации, либо обратитесь в техподдержку.";

            $this->includeComponentTemplate();
        }
        catch (Repository\NotFoundException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
                "request" => $request
            ];

            $this->arResult["EXCEPTIONS"][] =  "Пользователь не найден." .
                "<br>Пожалуйста, повторите процедуру региcтрации, либо обратитесь в техподдержку.";

            $this->includeComponentTemplate();
        }
        catch (SqlQueryException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
                "request" => $request
            ];

            $this->arResult["EXCEPTIONS"][] = "Внутреняя ошибка сервера при попытке регистрации. "
                ."<br>Пожалуйста, обратитесь в техподдержку или попробуйте повторить действие позже";

            $this->includeComponentTemplate();
        }
        catch (Exception $e) {
            $this->arResult["EXCEPTIONS"][] = "Внутреняя ошибка сервера при попытке регистрации. "
                ."<br>Пожалуйста, обратитесь в техподдержку или попробуйте повторить действие позже";

            $this->includeComponentTemplate();
        }

        return $exceptionLog;
    }

    private function restoreConfirmAction(HttpRequest $request) {
        $exceptionLog = [];

        try {
            $phone = $request->getPost("reg-restore-confirm-phone");
            $normalizedPhone = $this->normalize($phone);

            $repository = new UserRepository(Application::getConnection(), new DateTimeConverter());

            $user = $repository->getByPhone($normalizedPhone);

            $verificationCode = $user->generateVerificationCode();
            $repository->addVerification($user->getId(), $verificationCode);

            $this->throwStoreUserVerificationInitEvent($user);

            global $APPLICATION;
            LocalRedirect($APPLICATION->GetCurDir() . "?reg-confirm-form=yes&user={$user->getPhone()}");
        }
        catch (Repository\NotFoundException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
            ];

            $this->arResult["ERRORS"][] =  "По номеру $phone не найдано пользователя." .
                "<br>Пожалуйста, повторите процедуру региcтрации, либо обратитесь в техподдержку.";

            $this->includeComponentTemplate("restore-confirm");
        }
        catch (PhoneNormalizerException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
            ];

            $this->arResult["ERRORS"][] =  $exception;

            $this->includeComponentTemplate("restore-confirm");
        }
        catch (AlreadyVerifiedException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
            ];

            $this->arResult["ERRORS"][] =  $exception->getMessage() .
                "<br>Пожалуйста, повторите процедуру региcтрации, либо обратитесь в техподдержку.";

            $this->includeComponentTemplate("restore-confirm");
        }
        catch (SqlQueryException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
                "request" => $request
            ];

            $this->arResult["ERRORS"][] = "Внутреняя ошибка сервера при попытке регистрации. "
                ."<br>Пожалуйста, обратитесь в техподдержку или попробуйте повторить действие позже";

            $this->includeComponentTemplate("restore-confirm");
        }

        return $exceptionLog;
    }

    private function setDangerCss(RequestCommand $command) {
        $fields = [
            "email",
            "phone",
            "name",
            "password",
            "confirmPassword"
        ];

        foreach($fields as $field) {
            if(!$command->$field) {
                $this->arResult["DANGER_CSS"][$field] = "uk-form-danger";
            }
        }

        if($command->password !== $command->confirmPassword || strlen($command->password) < 6) {
            $this->arResult["DANGER_CSS"]["password"] = "uk-form-danger";
            $this->arResult["DANGER_CSS"]["confirmPassword"] = "uk-form-danger";
        }
    }

    private function throwCreateStoreUserEvent(User $user) {
        (new Event("","OnOipSocialStoreUserCreated", ["user" => $user]))->send();
    }

    private function throwStoreUserVerificationInitEvent(User $user) {
        (new Event("","OnOipSocialStoreUserVerificationInit", ["user" => $user]))->send();
    }

    private function throwCreateStoreUserErrorsEvent(array $errorLog) {
        (new Event("","OnOipSocialStoreUserCreateErrors", ["errors" => $errorLog]))->send();
    }
}

