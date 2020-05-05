<?php
/** @var array $arResult */
/** @var array $arParams */
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementOne */
/** @var \Oip\Custom\Component\Iblock\Element $element */
$chars = $element->getPropValue("CHARACTERISTICS");
?>

<ul class="uk-list uk-list-large uk-column-1-2@m">

    <?for($i = 0; $i < count($chars); $i++) {?>
        <li>
            <b><?=$element->getPropValueDescriptionFromMultiple("CHARACTERISTICS",$i)?>:</b><br>
            <?=$element->getPropValueFromMultiple("CHARACTERISTICS",$i)?>
        </li>
    <?}?>
</ul>
