<?php

use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;

require __DIR__ . "/../../../common/header.php";
require __DIR__ . "/init.php";

$ids = [];
$dbRes = CIBlockElement::GetList([],["IBLOCK_ID" => $iblockId, "PROPERTY_WARE_ID" => $wareId]);

while($el = $dbRes->Fetch()) {
    $ids[] = (int)$el["ID"];
}

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

$response = new Response(Status::createSuccess()->getValue(),json_encode($values));

echo $response->toJSON();

exit();