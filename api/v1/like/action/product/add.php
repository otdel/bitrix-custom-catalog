<?php

use Oip\RelevantProducts\DBDataSource;
use Oip\Util\Cache\NullCache;
use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;

require __DIR__ . "/../../../../common/header.php";

require __DIR__ . "/init.php";

$fuckCache = new NullCache();
$handler = new DBDataSource($DB, null, $fuckCache);
$handler->addProductLike($userId, $productId);

$response = new Response(Status::createSuccess()->getValue());

echo $response->toJSON();
exit();
