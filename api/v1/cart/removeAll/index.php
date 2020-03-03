<?php

require __DIR__ . "/../../../common/header.php";

use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;

$userId = (int)$_REQUEST["cartUserId"];

require  __DIR__ . "/../throws/userId.php";

require  __DIR__ . "/../init.php";

$cart->removeAll();

$response = new Response(Status::createSuccess()->getValue());

echo $response->toJSON();
exit();