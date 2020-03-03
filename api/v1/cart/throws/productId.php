<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var int $productId */

if(!$productId) {
    throw new InvalidArgumentException("Invalid productId. Check if you pass it.");
}