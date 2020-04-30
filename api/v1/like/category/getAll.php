<?php

use Oip\RelevantProducts\DBDataSource;
use Oip\Util\Cache\NullCache;
use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;

require __DIR__ . "/../../../common/header.php";

$sectionId = (int)$_REQUEST["sectionId"];

if(!$sectionId) {
    throw new InvalidArgumentException("Invalid sectionId. Check if you pass it.");
}

global $DB;

$fuckCache = new NullCache();
$handler = new DBDataSource($DB, null, $fuckCache);

$count = $handler->getSectionLikesCount($sectionId);

$response = new Response(Status::createSuccess()->getValue(),$count);

echo $response->toJSON();
exit();