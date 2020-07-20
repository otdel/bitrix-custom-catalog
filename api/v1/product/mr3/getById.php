<?php

use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;

require __DIR__ . "/../../../common/header.php";
require __DIR__ . "/init.php";

$ids = [];
$properties = [];


// выборка массива id товаров по wareId
// и массива свойств элемента для передачи в json на фронт
$dbRes = CIBlockElement::GetList([],["IBLOCK_ID" => $iblockId, "PROPERTY_WARE_ID" => $wareId], false, false,
    ["IBLOCK_ID","ID","PROPERTY_COLOR", "PROPERTY_COLOR"]);

while($el = $dbRes->Fetch()) {
    $ids[] = (int)$el["ID"];

    $color = [
        "featureName" => "Цвет",
        "featureCode" => "color",
        "featureValue" => $el["PROPERTY_COLOR_VALUE"],
    ];
    $properties[(int)$el["ID"]]["color"] = $color;

}

// выборка названий мр3 характеристик товаров
// и значений этих характеристик
$features = [];
foreach ($dw->getProductFeatures() as $feature) {
    $features[$feature->getCode()] = $feature->getName();
}

$values = [
    "features" => [],
    "values" => []
];
foreach ($dw->getProductFeatureValues($ids) as $productId => $featureValues) {

    /** @var \Oip\ProductFeature\ProductFeatureValue[] $featureValues */
    $values["values"][$productId] = [
        "productId" => $productId,
        "values" => []
    ];
    foreach ($featureValues as $featureValue) {
        $values["values"][$productId]["values"][] = [
            "featureName" => $features[$featureValue->getFeatureCode()],
            "featureCode" => $featureValue->getFeatureCode(),
            "featureValue" => $featureValue->getValue()
        ];
        $values["features"][$featureValue->getFeatureCode()] = $features[$featureValue->getFeatureCode()];
    }
}

// добавление в итоговый массив мр3 характеристик массива свойств элементов
$values["features"]["color"] = "Цвет";

foreach ($values["values"] as $productId => $vs) {
    $values["values"][$productId]["values"] = array_merge($vs["values"], $properties[$productId]);
}

$response = new Response(Status::createSuccess()->getValue(),json_encode($values));

echo $response->toJSON();

exit();