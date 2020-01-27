<?

$filterSectionFeatureValues = $arResult["filterSectionFeatureValues"];
$sectionFeatureOptions = $arResult["sectionFeatureOptions"];

?>

<form method='post'>
<input type='hidden' name='filter[action]' value='doFilter'>

    <? foreach ($filterSectionFeatureValues as $featureCode => $filterSectionFeature):

        // TODO: Пока забито гвоздями в коде, потом лучше вынести в БД
        // Стандартно, все фильтры - это чекбоксы
        $filterType = "checkbox";
        // Если характеристика - ширина, то фильтр вида "диапазон"
        if ($featureCode == "width") $filterType = "range"; ?>

        <p><?=$sectionFeatureOptions[$featureCode]->getFeatureName()?><br/>

            <? switch ($filterType):
                case "checkbox": ?>
                    <? foreach ($filterSectionFeature as $key => $value): ?>
                        <input type='checkbox' name='filter[filters][<?=$featureCode?>][<?=$key?>]' value='<?=$value?>' />
                        <label for='filter[filters][<?=$featureCode?>][<?=$key?>]'><?=$value?></label><br/>
                    <? endforeach; ?>
                    <? break; ?>

                <? case "range": ?>
                    <?
                    // Узнаем минимальное и максимальное значение для диапазона данной характеристики
                    $min = min($filterSectionFeature);
                    $max = max($filterSectionFeature);
                    ?>
                    <input type='hidden' value='<?=$min?>' name='filter[filters][<?=$featureCode?>][real_min]' />
                    <input type='hidden' value='<?=$max?>' name='filter[filters][<?=$featureCode?>][real_max]' />
                    <input type='number' value='<?=$min?>' min='<?=$min?>' max='<?=$max?>' name='filter[filters][<?=$featureCode?>][min]'  />
                    -
                    <input type='number' value='<?=$max?>' min='<?=$min?>' max='<?=$max?>' name='filter[filters][<?=$featureCode?>][max]'  />
                    <? break; ?>

                <? endswitch; ?>

        </p>

    <? endforeach ?>

    <input type='submit'>
</form>