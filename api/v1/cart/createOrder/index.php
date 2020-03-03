<?php
require __DIR__ . "/../../../common/header.php";

use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;
use Oip\SocialStore\Order\Entity\Order;
use Oip\SocialStore\Order\Status\Entity\Status as OrderStatus;

$userId = (int)$_REQUEST["cartUserId"];
require  __DIR__ . "/../throws/userId.php";

require  __DIR__ . "/../init.php";

$cart->getProducts();
$products = $cart->getProducts();

require  __DIR__ . "/../throws/productsCount.php";

require __DIR__ . "/../../order/initRepository.php";

$startStatus = $statusRepository->getByCode(OrderStatus::START_STATUS_CODE);
$order = new Order($user, $startStatus, $products);

$affectedCount = $orderRepository->addOrder($order);
if($affectedCount) {
    $cart->removeAll();
}

$response = new Response(Status::createSuccess()->getValue());

echo $response->toJSON();
exit();