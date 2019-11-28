<?
use Oip\RelevantProducts\RelevantProduct;
/** @var RelevantProduct[] $mostLikedProducts */
?>

<h3>Самые просматриваемые товары</h3>
<ol>
    <? foreach ($mostLikedProducts as $product): ?>
        <li>Товар #<?=$product->getId()?> (Просмотров: <?=$product->getViewsCount()?>) </li>
    <? endforeach; ?>
</ol>