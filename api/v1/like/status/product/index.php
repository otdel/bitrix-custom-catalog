<?php

use Oip\RelevantProducts\DBDataSource;
use Oip\Util\Cache\NullCache;
use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;

require __DIR__ . "/../../../../common/header.php";
require __DIR__ . "/init.php";

$fuckCache = new NullCache();
$handler = new DBDataSource($DB, null, $fuckCache);
$isLiked = $handler->isProductLikedByUser($productId, $userId);

$response = new Response(Status::createSuccess()->getValue(),(int)$isLiked);

echo $response->toJSON();
exit();
