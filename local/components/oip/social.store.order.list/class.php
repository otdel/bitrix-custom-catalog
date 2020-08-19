<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\SystemException;

use Oip\SocialStore\Order\Repository\DBRepository as OrderRepository;
use Oip\SocialStore\Order\Status\Repository\DBRepository as StatusRepository;
use Oip\SocialStore\Order\Handler as OrderHandler;

use Oip\SocialStore\User\Repository\DuplicateFoundException;
use Oip\Util\Serializer\ObjectSerializer\Base64Serializer;
use Oip\Util\Bitrix\DateTimeConverter;
use Oip\Util\Collection\Factory\CollectionsFactory;

use Oip\SocialStore\User\Entity\User;
use Oip\SocialStore\User\Repository\UserRepository;
use Bitrix\Main\Db\SqlQueryException;
use Oip\SocialStore\User\Repository\NotFoundException;

\CBitrixComponent::includeComponentClass("oip:component");

class COipSocialStoreOrderList extends \COipComponent {

    protected function initParams($arParams)
    {
        parent::initParams($arParams);

        $this->setDefaultParam($arParams["ON_PAGE"],10);
        $this->setDefaultParam($arParams["REDIRECT_URL"],"/");
        $this->setDefaultBooleanParam($arParams["SHOW_ALL"]);

        return $arParams;
    }

    /** @throws SystemException */
    public function executeComponent()
    {

        try {

            $storeClient = $this->getStoreClient();

            if(!$storeClient) {
                LocalRedirect($this->arParams["REDIRECT_URL"]);
            }

            $connection = Application::getConnection();
            $orderRepository = new OrderRepository($connection, new Base64Serializer(),
                new DateTimeConverter(), new CollectionsFactory());
            $statusRepository = new StatusRepository($connection);

            $handler = new OrderHandler($orderRepository, $statusRepository);

            $pageNumber = $this->getPageNumber($this->componentId);
            $this->arResult["COUNT"] = $count = $handler->getCountByUserId($storeClient->getId());

            $this->arResult["ORDERS"] = $handler->getAllByUserId(
                $storeClient->getId(),
                $pageNumber,
                $onPage = (!$this->isParam("SHOW_ALL")) ? $this->getParam("ON_PAGE") : $count
            );

            $pages = ceil($count/$onPage);
        }
        catch(NotFoundException $exception) {

            $this->arResult["EXCEPTION"] = "Ваши заказы не найдены. Если это ошибка, обратитесь пожалуйста в техподдержку.";

            $pageNumber = 1;
            $pages = 1;
        }
        catch (DuplicateFoundException $exception) {

            $this->arResult["EXCEPTION"] = "Ваши заказы не найдены. Если это ошибка, обратитесь пожалуйста в техподдержку.";

            $pageNumber = 1;
            $pages = 1;
        }

        $this->includeComponentTemplate();


        return [
            "NAV_ID" => $this->componentId,
            "PAGES" => $pages,
            "PAGE" => $pageNumber
        ];
    }

    /**
     * @return User|null
     * @throws SqlQueryException
     * @throws DuplicateFoundException
     * @throws NotFoundException
     */
    private function getStoreClient() {
        global $USER;

        if(!$USER->IsAuthorized()) {
            return null;
        }
        else {
            $repository = new UserRepository(Application::getConnection(), new DateTimeConverter());
            return $repository->getByBxId((int)$USER->GetID());
        }
    }

    /**
     * @param int $navId
     * @param int $navId
     * @return int
     *
     * @throws SystemException
     */
    protected function getPageNumber($navId) {
        $pageNumber = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->get("page_".$navId);

        return (int) ($pageNumber) ? $pageNumber : 1;
    }
}