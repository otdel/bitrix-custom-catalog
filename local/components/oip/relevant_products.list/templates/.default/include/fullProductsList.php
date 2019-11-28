<?
use Oip\RelevantProducts\RelevantSection;
/** @var RelevantSection[] $viewedSections */
?>

<h1>Просмотренные пользователем товары</h1>
<ol>
    <? foreach ($viewedSections as $viewedSection) :
        // Если есть фильтр по категориям - пропускаем ненужные
        if (isset($filterSections) && !in_array($viewedSection->getId(), $filterSections))  continue;
        ?>
        <li>
            Категория #<?=$viewedSection->getId()?>
            <ul>
                <? foreach ($viewedSection->getRelevantProducts() as $viewedProduct) { ?>
                    <li>Товар #<?=$viewedProduct->getId()?></li>
                <? } ?>
            </ul>
        </li>
    <? endforeach; ?>
</ol>