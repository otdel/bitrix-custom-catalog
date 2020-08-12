<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:component");

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Event;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\DB\SqlQueryException;

use Oip\SocialStore\Product\Entity\ProductCollection;

use Oip\Util\Serializer\ObjectSerializer\Base64Serializer;
use Oip\Util\Bitrix\DateTimeConverter;
use Oip\Util\Collection\Factory\CollectionsFactory;
use Oip\SocialStore\Order\Repository\RepositoryInterface as OrderRepositoryInterface;
use Oip\SocialStore\Order\Repository\DBRepository as OrderRepository;

use Oip\SocialStore\Order\Status\Entity\Status;
use Oip\SocialStore\Order\Status\Repository\DBRepository as StatusRepository;
use Oip\SocialStore\Order\Entity\Order;
use Oip\SocialStore\Order\Handler as OrderHandler;

class COipSocialStoreOrderAdd extends \COipComponent {

    /**
     * @param $arParams
     * @return array
     * @throws ArgumentNullException
     * @throws ArgumentTypeException
     */
    protected function initParams($arParams)
    {
        $arParams = parent::initParams($arParams);

        if(!is_set($arParams["USER_ID"])) {
            throw new ArgumentNullException("USER_ID");
        }

        if(!is_set($arParams["PRODUCTS"])) {
            throw new ArgumentNullException("PRODUCTS");
        }

        if(!($arParams["PRODUCTS"] instanceof ProductCollection)) {
            throw new ArgumentTypeException("PRODUCTS");
        }

        return $arParams;
    }


    /**
     * @return mixed|void
     * @throws SqlQueryException
     */
    public function executeComponent()
    {
        global $APPLICATION;
        global $USER;
        $loginLink = Configuration::getValue("oip_authorize_link");
        if(!$USER->IsAuthorized()) {
            return "You aren't logged in! Please, login to this "
                ."<a href='$loginLink?back_url={$APPLICATION->GetCurDir()}'>link</a>";
        }
        else {
            $connection = Application::getConnection();
            $orderRepository = $this->initOrderRepository();
            $statusRepository = new StatusRepository($connection);
            $startStatus = $this->getStartOrderStatus($statusRepository);

            $handler = new OrderHandler($orderRepository, $statusRepository);
            $order = new Order((int)$this->arParams['USER_ID'], $startStatus, $this->arParams['PRODUCTS']);

            $addedOrder = $handler->addOrder($order);
            $this->throwOrderCreatedEvent($addedOrder);

            return null;
        }
    }

    private function initOrderRepository(): OrderRepositoryInterface {
        $connection = Application::getConnection();
        $serializer = new Base64Serializer();
        $datetimeConverter = new DateTimeConverter();
        $ordersFactory  = new CollectionsFactory();

        return new OrderRepository($connection, $serializer, $datetimeConverter, $ordersFactory);
    }

    /**
     * @param StatusRepository $repository
     * @return Status
     * @throws SqlQueryException
     */
    private function getStartOrderStatus(StatusRepository $repository): Status {
        return $repository->getByCode(Status::START_STATUS_CODE);
    }

    private function throwOrderCreatedEvent(Order $order): void {
        (new Event("","OnOipSocialStoreOrderCreated", ["order" => $order]))->send();
    }
}