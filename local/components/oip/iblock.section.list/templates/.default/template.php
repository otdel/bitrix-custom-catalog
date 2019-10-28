<?php

use Oip\Custom\Component\Iblock\Section;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arResult */
/** @var Section[] $sections */
$sections = $arResult["SECTIONS"];
?>

<?php
/** @var Section[] $sections */
function printSection($sections) {
    echo "<ul>";
    /** @var Section $section */
    // Для каждого раздела выводим определенные поля, а также подразделы
    foreach ($sections as $section) {
        echo "<li style='background: " . rand_color() . "55;'>" . $section->getName() . " (ID: " . $section->getId() . ")<br>";

        // Пример вывода файла (изображения)
        $backgroundImage = $section->getPropValue("UF_BACKGROUND_IMAGE");
        echo $backgroundImage != null ? "<img src='" . $backgroundImage . "' width='150'/><br>" : "";

        // Пример вывода нескольких файлов (изображений)
        $images = $section->getPropValue("UF_TEST_FILES");
        if (is_array($images)) {
            foreach ($images as $image) {
               echo "<img src='" . $image . "' width='150'/> ";
            }
        }

        // Пример вывода значения из поля типа "список"
        $listValue = $section->getPropValue("UF_TEST_LIST");
        if (isset($listValue)) echo "<br><b>Пример вывода значения из поля типа \"список\"</b>: <br>" .  $listValue . "<br>";

        // Пример вывода нескольких значений из поля типа "список"
        $listValues = $section->getPropValue("UF_TEST_LISTS");
        if (isset($listValues)) {
            echo "<br><b>Пример вывода нескольких значений из поля типа \"список\"</b>: <br>";
            foreach ($listValues as $listValue) {
                echo $listValue . "<br>";
            }
            echo "<br>";
        }

        // Пример вывода значения "Привязка к элементу инфоблока"
        $iblockElement = $section->getPropValue("UF_TEST_INFOBLOCK_EL");
        if (isset($iblockElement)) {
            echo "<b>Пример вывода значения \"Привязка к элементу инфоблока\":</b><br>";
            echo "ID Элемента = " . $iblockElement . "<br>";
        }

        // Пример вывода значений "Привязка к элементу инфоблока"
        $iblockElements = $section->getPropValue("UF_TEST_ELEMENTS");
        if (isset($iblockElements)) {
            echo "<b>Пример вывода нескольких значений \"Привязка к элементу инфоблока\":</b><br>";
            foreach ($iblockElements as $key => $element) {
                echo "ID Элемента = " . $key . "<br>";
            }
        }

        // Пример вывода текстового значения
        $stringValue = $section->getPropValue("UF_BROWSER_TITLE");
        if (isset($stringValue)) {
            echo "<b>Заголовок браузера:</b> " . $stringValue . "<br>";
        }

        // Пример вывода множественного текстовго значения
        $stringValues = $section->getPropValue("UF_TEST_STRINGS");
        if (isset($stringValues)) {
            echo "<b>Пример вывода множественного текстовго значения:</b><br>";
            foreach ($stringValues as $stringValue) {
                echo $stringValue . "<br>";
            }
        }

        // Пример вывода числового значения
        $numberValue = $section->getPropValue("UF_TEST_NUMBER");
        if (isset($numberValue)) {
            echo "<b>Тестовое число:</b> " . $numberValue . "<br>";
        }

        // Пример вывода множественного числового значения
        $numberValues = $section->getPropValue("UF_TEST_NUMBERS");
        if (isset($numberValues)) {
            echo "<b>Пример вывода множественного числового значения:</b><br>";
            foreach ($numberValues as $numberValue) {
                echo $numberValue . "<br>";
            }
        }

        // Запросим подразделы
        $subSections = $section->getSubSections();

        // Если есть подразделы - выводим их
        if (isset($subSections)) {
            printSection($subSections);
        }
        echo "</li>";
    }
    echo "</ul>";
}

/**
 * Генерация рандомного цвета в HEX формате
 *
 * @return string
 */
function rand_color() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

printSection($sections);

?>

