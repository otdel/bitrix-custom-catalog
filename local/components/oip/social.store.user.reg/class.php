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

CBitrixComponent::includeComponentClass("oip:component");

class CSocialStoreUserReg extends COipComponent {

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

    private function handleAction(HttpRequest $request) {
        $exceptionLog = [];

        $action = ($request->getPost("reg-request-action"))
            ? "reg-request-action"
            : (($request->getPost("reg-confirm-action"))
                ? "reg-confirm-action"
                : (($request->get("reg-confirm-form"))
                    ? "reg-confirm-form"  : ""));

        switch($action) {
            case "reg-request-action":
                $exceptionLog = $this->regRequestAction($request);
            break;

            case "reg-confirm-action":
                $exceptionLog = $this->regConfirmAction($request);
            break;

            case "reg-confirm-form":
                $exceptionLog = $this->regConfirmForm($request);
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

            $this->throwCreateStoreUserEvent($newStoreUser);

            global $APPLICATION;
            LocalRedirect($APPLICATION->GetCurDir() . "?reg-confirm-form=yes&user={$newStoreUser->getPhone()}");
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
        }

        catch (StoreUserRegisterException $exception) {
            $exceptionLog = [
                "message" => $exception->getMessage(),
                "stackTrace" => $exception->getTraceAsString(),
            ];

            $this->arResult["ERRORS"][] = $exception;
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
            }

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

    private function throwCreateStoreUserEvent(User $user) {
        (new Event("","OnOipSocialStoreUserCreated", ["user" => $user]))->send();
    }

    private function throwCreateStoreUserErrorsEvent(array $errorLog) {
        (new Event("","OnOipSocialStoreUserCreateErrors", ["errors" => $errorLog]))->send();
    }
}

