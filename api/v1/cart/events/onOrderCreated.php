<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Oip\SocialStore\Order\Entity\Order;
use Bitrix\Main\Event;

/** @var Order $addedOrder */
(new Event("","OnOipSocialStoreOrderCreated", ["order" => $addedOrder]))->send();
