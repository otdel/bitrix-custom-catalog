<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;

/** @var array $arResult */

global $APPLICATION;
$request = Application::getInstance()->getContext()->getRequest();

$email = $request->getPost("store-user-reg-email");
$phone = $request->getPost("store-user-reg-phone");
$name = $request->getPost("store-user-reg-name");
$surname = $request->getPost("store-user-reg-surname");
$patronymic = $request->getPost("store-user-reg-patronymic");

$dangerCss = $arResult["DANGER_CSS"];
$dangerCssEmail = ($dangerCss && $dangerCss["email"]) ? $dangerCss["email"] : "";
$dangerCssPhone = ($dangerCss && $dangerCss["phone"]) ? $dangerCss["phone"] : "";
$dangerCssName = ($dangerCss && $dangerCss["name"]) ? $dangerCss["name"] : "";
$dangerCssPassword = ($dangerCss && $dangerCss["password"]) ? $dangerCss["password"] : "";
$dangerCssConfirmPassword = ($dangerCss && $dangerCss["confirmPassword"]) ? $dangerCss["confirmPassword"] : "";
?>

<?if($arResult["IS_AUTH"]):?>
    <div сlass="uk-alert-primary" uk-alert>
        <p>Вы авторизованы. Регистрация не требуется</p>
    </div>
<?else:?>

    <?if($arResult["EXCEPTIONS"]):?>

        <?foreach($arResult["EXCEPTIONS"] as $exception):?>
            <?$APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                "EXCEPTION" => $exception
            ])?>
        <?endforeach?>

        <div class="uk-margin">
            <a href='<?=$APPLICATION->GetCurDir()?>'>Назад к регистрации</a>
        </div>

    <?else:?>

        <?foreach($arResult["ERRORS"] as $error):?>
            <?$APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                "EXCEPTION" => $error
            ])?>
        <?endforeach?>

        <form method="post" action="">
            <fieldset class="uk-fieldset">
                <legend class="uk-legend">Регистрация</legend>

                <div class="uk-margin">
                    <input class="uk-input <?=$dangerCssEmail?> uk-form-width-medium" type="email" name="store-user-reg-email"
                           placeholder="Email" value="<?=$email?>">
                    <sup style="color:red">*</sup>
                </div>

                <div class="uk-margin">
                    <input class="uk-input <?=$dangerCssPhone?> uk-form-width-medium" type="tel" name="store-user-reg-phone"
                           placeholder="Телефон" value="<?=$phone?>">
                    <sup style="color:red">*</sup>
                </div>

                <div class="uk-margin">
                    <input class="uk-input <?=$dangerCssPassword?> uk-form-width-medium" type="password" name="store-user-reg-password"
                           placeholder="Пароль">
                    <sup style="color:red">*</sup>
                </div>

                <div class="uk-margin">
                    <input class="uk-input <?=$dangerCssConfirmPassword?> uk-form-width-medium" type="password" name="store-user-reg-confirm-password"
                           placeholder="Подтверждение пароля">
                    <sup style="color:red">*</sup>
                </div>

                <div class="uk-margin">
                    <input class="uk-input <?=$dangerCssName?> uk-form-width-medium" type="text" name="store-user-reg-name"
                           placeholder="Имя" value="<?=$name?>">
                    <sup style="color:red">*</sup>
                </div>

                <div class="uk-margin">
                    <input class="uk-input uk-form-width-medium" type="text" name="store-user-reg-surname" 
                           placeholder="Фамилия" value="<?=$surname?>">
                </div>

                <div class="uk-margin">
                    <input class="uk-input uk-form-width-medium" type="text" name="store-user-reg-patronymic" 
                           placeholder="Отчество" value="<?=$patronymic?>">
                </div>

                <div class="uk-margin">
                    <button type="submit" class="uk-button uk-button-primary" name="reg-request-action" value="1">Зарегистрироваться</button>
                </div>

                <div class="uk-margin">
                    Зарегистрировались, но не прошли верификацию?<br>
                    Перейдите по <a href="?restore-confirm-phone=yes">ссылке</a>, чтобы завершить процедуру верификации номера телефона.
                </div>

            </fieldset>
        </form>
    <?endif?>

<?endif?>
