<?php
/** @var \Oip\Custom\Component\Iblock\Element $element */

$advs = $element->getPropValue("ADVANTAGES");
?>

<div class="uk-panel">
    <dl class="uk-description-list uk-description-list-divider">

        <?for($i = 0; $i < count($advs); $i++) {?>
            <dt class="uk-text-primary"><b><?=$element->getPropValueDescriptionFromMultiple("ADVANTAGES",$i)?></b></dt>
            <dd><?=$element->getPropValueFromMultiple("ADVANTAGES",$i)?></dd>
        <?}?>
    </dl>
</div>
