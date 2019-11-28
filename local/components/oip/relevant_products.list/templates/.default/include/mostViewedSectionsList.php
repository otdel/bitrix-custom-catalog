<?
use Oip\RelevantProducts\RelevantSection;
/** @var RelevantSection[] $mostViewedSections */
?>

<h3>Самые просматриваемые категории</h3>
<ol>
    <? foreach ($mostViewedSections as $section) : ?>
        <li>Категория #<?=$section->getId()?> (Просмотров: <?=$section->getViewsCount()?>) </li>
    <? endforeach; ?>
</ol>