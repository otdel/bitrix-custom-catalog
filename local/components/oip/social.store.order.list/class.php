<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\ArgumentException;

use Oip\SocialStore\Order\Repository\DBRepository as OrderRepository;
use Oip\SocialStore\Order\Status\Repository\DBRepository as StatusRepository;
use Oip\SocialStore\Order\Handler as OrderHandler;

use Oip\Util\Serializer\ObjectSerializer\Base64Serializer;
use Oip\Util\Bitrix\DateTimeConverter;
use Oip\Util\Collection\Factory\CollectionsFactory;

\CBitrixComponent::includeComponentClass("oip:component");

class COipSocialStoreOrderList extends \COipComponent {

    protected function initParams($arParams)
    {
        try {
            if(!is_set($arParams["USER_ID"])) {
                throw new ArgumentNullException("USER_ID");
            }

            if(!(int)$arParams["USER_ID"]) {
                throw new ArgumentTypeException("USER_ID");
            }
        }
        catch (ArgumentException $exception) {
            $this->arResult["EXCEPTION"] = $exception->getMessage();
        }

        return $arParams;
    }

    public function executeComponent()
    {
        $connection = Application::getConnection();
        $orderRepository = new OrderRepository($connection, new Base64Serializer(),
            new DateTimeConverter(), new CollectionsFactory());
        $statusRepository = new StatusRepository($connection);

        $handler = new OrderHandler($orderRepository, $statusRepository);

        $this->arResult["ORDERS"] = $handler->getAllByUserId((int)$this->arParams["USER_ID"]);

        $this->includeComponentTemplate();
    }
}