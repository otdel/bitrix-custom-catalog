<?php

$userId = (int)$_REQUEST["userId"];
$productId = (int)$_REQUEST["productId"];

if(!$productId) {
    throw new InvalidArgumentException("Invalid productId. Check if you pass it.");
}

if(!$userId) {
    throw new InvalidArgumentException("Invalid userId. Check if you pass it.");
}

global $DB;
