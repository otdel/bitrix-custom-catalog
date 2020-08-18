<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arResult */
?>

<?if($arResult["ERRORS"] ):?>
    <?foreach($arResult["ERRORS"] as $error):?>
        <?$APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
            "EXCEPTION" => $error
        ])?>
    <?endforeach?>
<?endif?>

<form action="" method="post">
    <fieldset class="uk-fieldset">
        <legend class="uk-legend">Подтвердите номер телефона</legend>

        <div class="uk-margin">
            <input class="uk-input uk-form-width-medium" type="tel" name="reg-restore-confirm-phone"
                   placeholder="Телефон" value="" required>
            <sup style="color:red">*</sup>
        </div>

        <div class="uk-margin">
            <button type="submit" class="uk-button uk-button-primary" name="restore-confirm-phone-action" value="1">Подтвердить</button>
        </div>

    </fieldset>


</form>

