<?php

use Oip\Util\Serializer\ObjectSerializer\Base64Serializer;
use Oip\Util\Bitrix\DateTimeConverter;
use Oip\Util\Collection\Factory\CollectionsFactory;
use Oip\SocialStore\Order\Repository\DBRepository as OrderRepository;
use Oip\SocialStore\Order\Status\Repository\DBRepository as StatusRepository;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once __DIR__ . "/../../common/connection.php";

$serializer = new Base64Serializer();
$datetimeConverter = new DateTimeConverter();
$ordersFactory  = new CollectionsFactory();

$orderRepository = new OrderRepository($connection, $serializer, $datetimeConverter, $ordersFactory);
$statusRepository = new StatusRepository($connection);