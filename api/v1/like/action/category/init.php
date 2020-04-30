<?php

$userId = (int)$_REQUEST["userId"];
$sectionId = (int)$_REQUEST["sectionId"];

if(!$sectionId) {
    throw new InvalidArgumentException("Invalid sectionId. Check if you pass it.");
}

if(!$userId) {
    throw new InvalidArgumentException("Invalid userId. Check if you pass it.");
}

global $DB;
