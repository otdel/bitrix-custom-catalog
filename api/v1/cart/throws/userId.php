<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var int $userId */

if(!$userId) {
    throw new InvalidArgumentException("Invalid userId. Check if you pass it.");
}