<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arResult */
/** @var array $arParams */
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipPageNavigation */

$exception = $arResult["EXCEPTION"];

$navId = $arParams["NAV_ID"];
$page = $arParams["PAGE"];
$pages = $arParams["PAGES"];

$params = $arResult["PARAMS"];
?>

<?if($exception):?>
    <p><?=$exception?></p>
<?else:?>
    <ul class="uk-pagination">

        <?if($page != 1):?>
            <li><a href="<?=$component->generateLink($params,$navId,$page-1)?>"><span uk-pagination-previous></span></a></li>
        <?endif?>

        <?for($i = 1; $i <= $pages; $i++) {?>

            <?if($page == $i):?>
                <li class="uk-active"><span><?=$i?></span></li>
            <?else:?>
                <li><a href="<?=$component->generateLink($params,$navId,$i)?>"><?=$i?></a></li>
            <?endif?>
        <?}?>

        <?if($page < $pages):?>
            <li><a href="<?=$component->generateLink($params,$navId,$page+1)?>"><span uk-pagination-next></span></a></li>
        <?endif?>
    </ul>
<?endif?>




