<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;
use Oip\SocialStore\User\UseCase\Register\Request\Command;
use Oip\SocialStore\User\UseCase\Register\Request\Handler;
use Oip\SocialStore\User\Repository\UserRepository;
use Oip\SocialStore\User\UseCase\Register\Request\StoreUserRegisterException;
use Oip\SocialStore\User\UseCase\Register\Request\UserExistByPhoneException;
use Oip\SocialStore\User\UseCase\Register\Request\UserExistByEmailException;
use Bitrix\Main\Db\SqlQueryException;
use Oip\SocialStore\User\Entity\User;
use Bitrix\Main\Event;

\CBitrixComponent::includeComponentClass("oip:component");

class CSocialStoreUserReg extends \COipComponent {

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
        }
        else {

            $request = Application::getInstance()->getContext()->getRequest();

            if($request->getPost("store-user-reg")) {

                $regCommand = new Command;
                $regCommand->email = $request->getPost("store-user-reg-email");
                $regCommand->phone = $request->getPost("store-user-reg-phone");
                $regCommand->password = $request->getPost("store-user-reg-password");
                $regCommand->confirmPassword = $request->getPost("store-user-reg-confirm-password");
                $regCommand->name = $request->getPost("store-user-reg-name");
                $regCommand->surname = $request->getPost("store-user-reg-surname");
                $regCommand->patronymic = $request->getPost("store-user-reg-patronymic");

                $repository = new UserRepository(Application::getConnection());
                $handler = new Handler($repository);

                try {
                    $newStoreUser = $handler->handle($regCommand);

                    $this->throwCreateStoreUserEvent($newStoreUser);

                    global $USER;
                   if($USER->Authorize($newStoreUser->getBxId())) {
                       LocalRedirect($this->arParams["BACK_URL"]);
                   }
                   else {
                       $this->arResult["EXCEPTIONS"][] = "Вы успешно зарегистрировались, "
                           ."но произошла внутреняя ошибка сервера при попытке аутентификации. "
                           ."Вы можете воспользоваться номером телефона или почтой для входа в магазин,"
                           ." пройдя по этой <a href='{$this->arParams['AUTH_LINK']}'>ссылке</a>";
                   }
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

                catch (SqlQueryException $exception) {
                    $exceptionLog = [
                        "message" => $exception->getMessage(),
                        "stackTrace" => $exception->getTraceAsString(),
                    ];

                    $this->arResult["ERRORS"][] = "Внутреняя ошибка сервера при попытке регистрации. "
                        ."Пожалуйста, обратитесь в техподдержку или попробуйте повторить действие позже";
                }
            }

        }

        $this->includeComponentTemplate();

        return $exceptionLog;
    }

    private function throwCreateStoreUserEvent(User $user) {
        (new Event("","OnOipSocialStoreUserCreated", ["user" => $user]))->send();
    }
}

