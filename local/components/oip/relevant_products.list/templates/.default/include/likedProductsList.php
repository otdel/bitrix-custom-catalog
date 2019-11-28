<?
use Oip\RelevantProducts\RelevantSection;
/** @var RelevantSection[] $likedSections */
?>

<h1>Список лайкнутых товаров</h1>
<ol>
    <? foreach ($likedSections as $section) : ?>
        <h3>Категория #<?=$section->getId()?> (Лайков: <?=$section->getLikesCount()?>)</h3>
        <ul>
            <? foreach ($section->getRelevantProducts() as $product) { ?>
                <? if ($product->getLikesCount() == 0) continue;  ?>
                <li>Товар #<?=$product->getId()?></li>
            <? } ?>
        </ul>
    <? endforeach; ?>
</ol>