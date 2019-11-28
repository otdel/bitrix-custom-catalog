<?
use Oip\RelevantProducts\RelevantSection;
/** @var RelevantSection[] $newProductsSections */
?>

<h3>Новые товары из топ-10 категорий</h3>
<ol>
    <?
    foreach ($newProductsSections as $newProductsSection) { ?>
        <li>Категория #<?=$newProductsSection->getId()?>
            <ul>
                <? foreach ($newProductsSection->getRelevantProducts() as $product) { ?>
                    <li>Товар #<?=$product->getId()?> </li>
                <? } ?>
            </ul>
        </li>
    <? } ?>
</ol>