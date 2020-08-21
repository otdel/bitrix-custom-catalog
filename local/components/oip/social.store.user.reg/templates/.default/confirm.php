<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arResult */
/** @var array $arParams */

global $APPLICATION;
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
            <a href='<?=$APPLICATION->GetCurDir()?>?back_url=<?=$arParams["BACK_URL"]?>'>Назад к регистрации</a>
        </div>
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

                <input type="hidden" name="store-user-reg-confirm-user" value="<?=$arResult["USER_PHONE"]?>">
                <input type="hidden" name="back_url" value="<?=$arParams["BACK_URL"]?>">

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
                    <a href="?reg-confirm-form=yes&user=<?=$arResult["USER_PHONE"]?>&code-resend=yes&back_url=<?=$arParams["BACK_URL"]?>">Выслать новый код</a>
                </uk>

                <div class="uk-margin">
                    <a href='<?=$APPLICATION->GetCurDir()?>?back_url=<?=$arParams["BACK_URL"]?>'>Назад к регистрации</a>
                </div>

            </fieldset>
        </form>

    <?endif?>
<?endif?>
