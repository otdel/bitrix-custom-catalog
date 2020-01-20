<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->IncludeComponent("oip:guest.user.processor.init",
    "",[]);

$APPLICATION->IncludeComponent("oip:social.store.cart.processor","",[]);