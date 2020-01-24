<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\EventManager;

$em = EventManager::getInstance();

require($_SERVER['DOCUMENT_ROOT'] . '/local/include/init_app.php');

require($_SERVER['DOCUMENT_ROOT'] . '/local/include/epilog_app.php');