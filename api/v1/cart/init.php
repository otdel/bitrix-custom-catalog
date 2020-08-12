<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var int $userId */
/** @var Bitrix\Main\DB\Connection $connection */

require __DIR__ . "/../../common/connection.php";

use Bitrix\Main\Config\Configuration;
use Oip\SocialStore\Cart\Handler as Cart;
use Oip\SocialStore\Cart\Repository\DBRepository as CartRepository;
use Oip\Util\Collection\Factory\CollectionsFactory as ProductsFactory;
use Oip\Util\Bitrix\Iblock\ElementPath\Helper as PathHelper;

$iblockId = Configuration::getValue("oip_catalog_iblock_id");

$pathHelper = new PathHelper($connection, $iblockId);
$repository = new CartRepository($connection, $pathHelper);
$productCollection = ProductsFactory::createByObjects([], "Oip\SocialStore\Product\Entity\ProductCollection");
$cart = new Cart($userId, $productCollection, $repository, Oip\App::getPriceProvider());