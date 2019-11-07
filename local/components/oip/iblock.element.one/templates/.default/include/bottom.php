<?php
    /** @var \Oip\Custom\Component\Iblock\Element $element */
?>

<ul uk-tab class="uk-margin-large-top">
    <?if($element->getPropValue("ADVANTAGES")):?>
        <li><a href="#">Преимущества</a></li>
    <?endif?>

    <?if($element->getPropValue("CHARACTERISTICS")):?>
        <li><a href="#">Характеристики</a></li>
    <?endif?>


    <?if($element->getPropValue("REVIEWS")):?>
        <li><a href="#">Отзывы (<?=$element->getPropValueCount("REVIEWS")?>)</a></li>
    <?endif?>
</ul>


<ul class="uk-switcher uk-margin">

    <?if($element->getPropValue("ADVANTAGES")):?>
        <li>
            <?include_once (__DIR__."/advantages.php")?>
        </li>
    <?endif?>


    <?if($element->getPropValue("CHARACTERISTICS")):?>
        <li>
            <?include_once (__DIR__."/characteristics.php")?>
        </li>
    <?endif?>

    <?if($element->getPropValue("REVIEWS")):?>
        <?include_once (__DIR__."/reviews.php")?>
    <?endif?>

</ul>
