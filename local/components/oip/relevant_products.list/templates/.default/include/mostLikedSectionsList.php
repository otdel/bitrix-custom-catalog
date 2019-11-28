<?
use Oip\RelevantProducts\RelevantSection;
/** @var RelevantSection[] $mostLikedSections */
?>

<h3>Самые залайканные категории</h3>
<ol>
    <? foreach ($mostLikedSections as $section) : ?>
        <li>Категория #<?=$section->getId()?> (Лайков: <?=$section->getLikesCount()?>) </li>
    <? endforeach; ?>
</ol>