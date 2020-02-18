<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Oip\RelevantProducts\DataWrapper;

/** @var array $arResult */
/** @var Exception $exception */
/** @var CBitrixComponentTemplate $this  */
/** @var CRelevantProductsViewsProductCount $component  */

$exception = $arResult["EXCEPTION"];
$views = (is_null($exception)) ? $arResult["VIEWS"] : null;
?>

<?if(!is_null($exception)):?>
    <?$APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
        "EXCEPTION" => $exception
    ])?>
<?else:?>
   <?=$views?>&nbsp;<span class="uk-icon-button uk-margin-small-right"
            uk-icon="star"
            uk-tooltip="<?=$component->getNumWord($views, ["просмотр","просмотра","просмотров"])?>"></span>
<?endif?>