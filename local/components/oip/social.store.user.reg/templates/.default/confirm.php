<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arResult */
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

        <?if($arResult["ERRORS"]):?>
            <?foreach($arResult["ERRORS"] as $error):?>
                <?$APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                    "EXCEPTION" => $error
                ])?>
            <?endforeach?>
        <?endif?>

        <form method="post" action="">
            <fieldset class="uk-fieldset">
                <legend class="uk-legend">Подтверждение номера телефона</legend>

                <input type="hidden" name="store-user-reg-confirm-user" value="<?=$arResult["USER_ID"]?>">

                <?if(!$arResult["ERRORS"] || empty($arResult["ERRORS"])):?>
                    <div class="uk-alert-primary" uk-alert>
                        <p>
                            На указанный вами номер телефона <?=$arResult["USER_PHONE"]?> выслан код верификации.<br>
                            Пожалуйста, введите код в поле ниже для подтверждения номера телефона.
                        </p>
                    </div>
                <?endif?>

                <div class="uk-margin">
                    <input class="uk-input uk-form-width-medium" min="1" maxlength="6" type="number" name="store-user-reg-confirm-code"
                           placeholder="Код верификации">
                </div>

                <div class="uk-margin">
                    <button type="submit" class="uk-button uk-button-primary" name="reg-confirm-action" value="1">Подтвердить</button>
                </div>

                <uk class="margin">
                    <a href="?reg-confirm-form=yes&user-id=<?=$arResult["USER_ID"]?>&code-resend=yes">Выслать новый код</a>
                </uk>

            </fieldset>
        </form>

    <?endif?>
<?endif?>
