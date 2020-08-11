<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;

/** @var array $arResult */
$request = Application::getInstance()->getContext()->getRequest();

$email = $request->getPost("store-user-reg-email");
$phone = $request->getPost("store-user-reg-phone");
$name = $request->getPost("store-user-reg-name");
$surname = $request->getPost("store-user-reg-surname");
$patronymic = $request->getPost("store-user-reg-patronymic");
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
    <?else:?>

        <?foreach($arResult["ERRORS"] as $error):?>
            <?if(is_string($error)):?>
                <?$APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                    "CUSTOM_MESSAGE" => $error
                ])?>
            <?else:?>
                <?$APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                    "EXCEPTION" => $error
                ])?>
            <?endif?>
        <?endforeach?>

        <form method="post" action="">
            <fieldset class="uk-fieldset">
                <legend class="uk-legend">Регистрация</legend>

                <div class="uk-margin">
                    <input class="uk-input uk-form-width-medium" type="email" name="store-user-reg-email" 
                           placeholder="Email" value="<?=$email?>">
                    <sup style="color:red">*</sup>
                </div>

                <div class="uk-margin">
                    <input class="uk-input uk-form-width-medium" type="tel" name="store-user-reg-phone" 
                           placeholder="Телефон" value="<?=$phone?>">
                    <sup style="color:red">*</sup>
                </div>

                <div class="uk-margin">
                    <input class="uk-input uk-form-width-medium" type="password" name="store-user-reg-password" 
                           placeholder="Пароль">
                    <sup style="color:red">*</sup>
                </div>

                <div class="uk-margin">
                    <input class="uk-input uk-form-width-medium" type="password" name="store-user-reg-confirm-password"
                           placeholder="Подтверждение пароля">
                    <sup style="color:red">*</sup>
                </div>

                <div class="uk-margin">
                    <input class="uk-input uk-form-width-medium" type="text" name="store-user-reg-name" 
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
                    <button type="submit" class="uk-button uk-button-primary" name="store-user-reg" value="1">Регистрация</button>
                </div>

            </fieldset>
        </form>
    <?endif?>

<?endif?>
