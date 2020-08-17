<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var array $arParams */
/** @var Exception $exception */
/** @var $this CBitrixComponentTemplate */
/** @var $component CSystemExceptionViewer */

$debugMode = ($arParams["DEBUG_MODE"] === "N") ? false : true;
$exception = $arResult["EXCEPTION"];
?>

<?if($exception):?>

    <?if(is_string($exception)):?>
        <div class="uk-alert-danger" uk-alert>
            <p><?=htmlspecialcharsback($exception)?></p>
        </div>
    <?else:?>
        <div class="uk-alert-danger" uk-alert>
            <p><?=$exception->getMessage()?></p>
        </div>
        <?if($debugMode):?>
            <div uk-alert>
                <em><small>(Это не ошибка, а стек вызовов для локальной профилировки исключений - на проде не будет)</small></em>
                <pre><?=$exception->getTraceAsString()?></pre>
            </div>
        <?endif?>
    <?endif?>

<?endif?>
