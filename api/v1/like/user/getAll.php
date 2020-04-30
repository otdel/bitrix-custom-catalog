<?php

use Oip\RelevantProducts\DBDataSource;
use Oip\Util\Cache\NullCache;
use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;

require __DIR__ . "/../../../common/header.php";

$userId = (int)$_REQUEST["userId"];

if(!$userId) {
    throw new InvalidArgumentException("Invalid userId. Check if you pass it.");
}

global $DB;

$fuckCache = new NullCache();
$handler = new DBDataSource($DB, null, $fuckCache);

$count = $handler->getUserLikes($userId);

$response = new Response(Status::createSuccess()->getValue(),$count);

echo $response->toJSON();
exit();