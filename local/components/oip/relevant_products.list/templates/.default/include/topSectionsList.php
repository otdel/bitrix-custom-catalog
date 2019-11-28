<?
use Oip\RelevantProducts\RelevantSection;
/** @var RelevantSection[] $viewedSections */
?>

<? foreach ($viewedSections as $section) : ?>
    <h3>Категория <?=$section->getId()?></h3>
    <p>Вес (сила интереса): <?=$section->getWeight();?> (Просмотров: <?=$section->getViewsCount();?> , лайков:  <?=$section->getLikesCount();?>)</p>
<? endforeach; ?>