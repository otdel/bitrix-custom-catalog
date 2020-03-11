<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:component");

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Event;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\DB\SqlQueryException;

use Oip\SocialStore\User\Entity\User;
use Oip\SocialStore\Product\Entity\ProductCollection;

use Oip\Util\Serializer\ObjectSerializer\Base64Serializer;
use Oip\Util\Bitrix\DateTimeConverter;
use Oip\Util\Collection\Factory\CollectionsFactory;
use Oip\SocialStore\Order\Repository\RepositoryInterface as OrderRepositoryInterface;
use Oip\SocialStore\Order\Repository\DBRepository as OrderRepository;

use Oip\SocialStore\Order\Status\Entity\Status;
use Oip\SocialStore\Order\Status\Repository\DBRepository as StatusRepository;
use Oip\SocialStore\Order\Entity\Order;

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

        if(!is_set($arParams["USER"])) {
            throw new ArgumentNullException("USER");
        }

        if(!is_set($arParams["PRODUCTS"])) {
            throw new ArgumentNullException("PRODUCTS");
        }

        if(!($arParams["USER"] instanceof User)) {
            throw new ArgumentTypeException("USER");
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
            $repository = $this->initOrderRepository();
            $startStatus = $this->getStartOrderStatus();
            $order = new Order($this->arParams['USER'], $startStatus, $this->arParams['PRODUCTS']);
            if($newOrderId = $repository->addOrder($order)) {
                $order = $repository->getById($newOrderId);
                $this->throwOrderCreatedEvent($order);
            }

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
     * @return Status
     * @throws SqlQueryException
     */
    private function getStartOrderStatus(): Status {
        $connection = Application::getConnection();
        $statusRep = new StatusRepository($connection);
        return $statusRep->getByCode(Status::START_STATUS_CODE);
    }

    private function throwOrderCreatedEvent(Order $order): void {
        (new Event("","OnOipSocialStoreOrderCreated", ["order" => $order]))->send();
    }
}