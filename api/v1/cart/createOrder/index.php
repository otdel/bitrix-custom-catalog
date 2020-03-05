<?php
require __DIR__ . "/../../../common/header.php";

use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;
use Oip\SocialStore\Order\Entity\Order;
use Oip\SocialStore\Order\Status\Entity\Status as OrderStatus;

global $USER;

if(!$USER->IsAuthorized()) {
    $response = new Response(Status::createError()->getValue(),null,
        "You aren't signed in. Please, sign in to create order.");
}
else {
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

        require __DIR__ . "/../events/onOrderCreated.php";
    }

    $response = new Response(Status::createSuccess()->getValue());
}

echo $response->toJSON();
exit();