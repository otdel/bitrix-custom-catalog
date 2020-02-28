<?php

require __DIR__ . "/../../../common/header.php";

use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;
use Oip\Util\Serializer\ObjectReflector;

$userId = (int)$_REQUEST["cartUserId"];

require  __DIR__ . "/../init.php";

$cart->getProducts();
$products = $cart->getProducts()->getArray();

$response = Response::create(new ObjectReflector(), Status::createSuccess(), $products);

echo $response->toJSON();
exit();