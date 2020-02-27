<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require __DIR__ . "/../../../common/connection.php";

use Bitrix\Main\Config\Configuration;
use Oip\SocialStore\Cart\Handler as Cart;
use Oip\SocialStore\User\Entity\User as CartUser;
use Oip\SocialStore\Cart\Repository\DBRepository as CartRepository;
use Oip\Util\Collection\Factory\CollectionsFactory as ProductsFactory;
use Oip\Util\Bitrix\Iblock\ElementPath\Helper as PathHelper;

$iblockId = Configuration::getValue("oip_catalog_iblock_id");

$pathHelper = new PathHelper($connection, $iblockId);
$repository = new CartRepository($connection, $pathHelper);
$user = new CartUser($userId);
$productCollection = ProductsFactory::createByObjects([], "Oip\SocialStore\Product\Entity\ProductCollection");
$cart = new Cart($user, $productCollection, $repository);