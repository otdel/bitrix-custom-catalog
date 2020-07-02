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
$skip = false;

$printNav = function($i, $page) use ($component, $params,$navId) {?>
    <?if($page == $i):?>
        <li class="uk-active"><span><?=$i?></span></li>
    <?else:?>
        <li><a href="<?=$component->generateLink($params,$navId,$i)?>"
               data-filter-id="<?=$navId?>"
               data-page="<?=$i?>"
               class="oip-page-apply"><?=$i?></a></li>
    <?endif?>
<?}
?>

<?if($exception):?>
    <p><?=$exception?></p>
<?else:?>
    <ul class="uk-pagination">

        <?if($page != 1):?>
            <li><a href="<?=$component->generateLink($params,$navId,$page-1)?>"
                   data-filter-id="<?=$navId?>"
                   data-page="<?=$page-1?>"
                   class="oip-page-apply"><span uk-pagination-previous></span></a></li>
        <?endif?>

        <?for($i = 1; $i <= $pages; $i++) {?>
            
            
            <?if($page <=3):?>
                <?if($i > 3 && $i < $pages):?>
                    <?if($i == 4):?>
                        <li>...</li>
                    <?endif?>
                <?else:?>
                    <?$printNav($i, $page);?>
                <?endif?>
            <?elseif(($pages - $page) < 3):?>
                <?if($i > 1 && $i < ($pages - 2)):?>
                    <?if($i == ($pages - 3)):?>
                        <li>...</li>
                    <?endif?>
                <?else:?>
                    <?$printNav($i, $page);?>
                <?endif?>
            <?else:?>
                <?if($i == 1 || $i == $pages || $i == $page || $i == ($page - 1) || $i == ($page + 1)):?>
                    <?$printNav($i, $page);?>
                <?else:?>
                    <?if($i == ($page - 2) || $i == ($page + 2)):?>
                        <li>...</li>
                    <?endif?>
                <?endif?>
            <?endif?>
        <?}?>

        <?if($page < $pages):?>
            <li><a href="<?=$component->generateLink($params,$navId,$page+1)?>"
                   data-filter-id="<?=$navId?>"
                   data-page="<?=$page+1?>"
                   class="oip-page-apply"><span uk-pagination-next></span></a></li>
        <?endif?>
    </ul>
<?endif?>




