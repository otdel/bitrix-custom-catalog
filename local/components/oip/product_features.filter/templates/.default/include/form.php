<?

$filterSectionFeatureValues = $arResult["filterSectionFeatureValues"];
$sectionFeatureOptions = $arResult["sectionFeatureOptions"];

$pfFilters = $_GET["pf"];
?>

<? if ($filterSectionFeatureValues): ?>
<form method='get' id="data-filter-id" <?=!empty($arParams["NAV_ID"]) ? "value=\"" . $arParams['NAV_ID'] . "\"" : ""?>>
    <input type='hidden' value='<?=$arParams['NAV_ID']?>' id='data-filter-pfeatures-id' />

    <? foreach ($filterSectionFeatureValues as $featureCode => $filterSectionFeature):
        // Убираем пустые значения
        $filterSectionFeature = array_filter($filterSectionFeature);

        // TODO: Пока забито гвоздями в коде, потом лучше вынести в БД
        // Стандартно, все фильтры - это чекбоксы
        $filterType = "checkbox";
        // Для некоторых характеристик устанавливать диапазонный фильтр
        if ($featureCode == "width" || $featureCode == "enginePerformance") $filterType = "range";
        ?>

        <p><?=$sectionFeatureOptions[$featureCode]->getFeatureName()?><br/>

            <? switch ($filterType):
                case "checkbox": ?>
                    <? foreach ($filterSectionFeature as $key => $value):
                        $propertyName = "pf[$featureCode][$key]";
                        $isActive = isset($pfFilters[$featureCode][$key]);
                    ?>
                        <input type='checkbox' name='<?=$propertyName?>' value='<?=$value?>' class="oip-filter-pfeature-item" <?=($isActive ? "checked" : "")?>/>
                        <label for='pf[<?=$featureCode?>][<?=$key?>]'><?=!empty($value) ? $value : "Не установлено"?></label><br/>
                    <? endforeach; ?>
                    <? break; ?>

                <? case "range": ?>
                    <?
                    // Узнаем минимальное и максимальное значение для диапазона данной характеристики
                    $min = min($filterSectionFeature);
                    $max = max($filterSectionFeature);
                    $selectedMin = $min;
                    $selectedMax = $max;
                    // Если есть активный фильтр - восстановим его на форме. Не даем превысить реальные границы диапазона
                    if (!empty($pfFilters[$featureCode]["min"]) ) {
                        $selectedMin = max($min, min($pfFilters[$featureCode]["min"], $max));
                    }
                    if (!empty($pfFilters[$featureCode]["max"]) ) {
                        $selectedMax = min($max, max($pfFilters[$featureCode]["max"], $min));
                    }
                    ?>
                    <input type='hidden' value='<?=$min?>' name='pf[<?=$featureCode?>][real_min]'/>
                    <input type='hidden' value='<?=$max?>' name='pf[<?=$featureCode?>][real_max]' />
                    <input type='number' value='<?=$selectedMin?>' min='<?=$min?>' max='<?=$max?>' name='pf[<?=$featureCode?>][min]' class="oip-filter-pfeature-item-range" />
                    -
                    <input type='number' value='<?=$selectedMax?>' min='<?=$min?>' max='<?=$max?>' name='pf[<?=$featureCode?>][max]' class="oip-filter-pfeature-item-range" />
                    <? break; ?>

                <? endswitch; ?>

        </p>

    <? endforeach ?>

        <a class="uk-button uk-button-link uk-button-small oip-filter-pfeatures-apply" href="javascript:void(0)" id="oip-filter-pfeatures-apply" <?=!empty($arParams["NAV_ID"]) ? "data-filter-id=\"" . $arParams['NAV_ID'] . "\"" : ""?>>Выбрать</a>
        <a class="uk-button uk-button-link uk-button-small oip-filter-pfeatures-reset" href="javascript:void(0)" id="oip-filter-pfeatures-reset" <?=!empty($arParams["NAV_ID"]) ? "data-filter-id=\"" . $arParams['NAV_ID'] . "\"" : ""?>>Сбросить</a>

</form>
<? endif; ?>