<?php

use Oip\SocialStore\Product\Entity\ProductCollection;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var ProductCollection $products */
/** @var int $userId */

if (!$products->count()) {
    throw new DomainException("Unable to create an order because the user $userId cart is empty.");
}