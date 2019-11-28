<?
use Oip\RelevantProducts\RelevantProduct;
/** @var RelevantProduct[] $mostLikedProducts */
?>

<h3>Самые залайканные товары</h3>
<ol>
    <?
    foreach ($mostLikedProducts as $product) : ?>
        <li>Товар #<?=$product->getId()?> (Лайков: <?=$product->getLikesCount()?>) </li>
    <? endforeach; ?>
</ol>