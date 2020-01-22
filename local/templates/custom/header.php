<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Page\Asset;
?>

<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>"  prefix="og: http://ogp.me/ns#" xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">

    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />

    <?$APPLICATION->ShowHead();?>

    <?
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/uikit.min.css");

    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/custom/main.css");

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/uikit.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/uikit-icons.min.js");

    Asset::getInstance()->addJs("/local/js/oip-lib.js");

    require($_SERVER['DOCUMENT_ROOT'] . '/local/include/init_app.php');
    ?>

    <title><?$APPLICATION->ShowTitle()?></title>
</head>

<body>