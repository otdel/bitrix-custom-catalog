<?
use Oip\RelevantProducts\RelevantSection;
/** @var RelevantSection $topSection */
?>

<ul>
    <li>Категория <?=$topSection->getId()?>
        <ol>
            <? foreach ($topSection->getRelevantProducts() as $product) : ?>
                <li>Товар #<?=$product->getId()?> (Просмотров: <?=$product->getViewsCount()?>, лайков: <?=$product->getLikesCount()?>)</li>
            <? endforeach; ?>
        </ol>
    </li>
</ul>