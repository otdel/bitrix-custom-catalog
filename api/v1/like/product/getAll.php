<?php

use Oip\RelevantProducts\DBDataSource;
use Oip\Util\Cache\NullCache;
use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;

require __DIR__ . "/../../../common/header.php";

$productId = (int)$_REQUEST["productId"];

if(!$productId) {
    throw new InvalidArgumentException("Invalid productId. Check if you pass it.");
}

global $DB;

$fuckCache = new NullCache();
$handler = new DBDataSource($DB, null, $fuckCache);

$count = $handler->getProductLikes($productId);

$response = new Response(Status::createSuccess()->getValue(),$count);

echo $response->toJSON();
exit();