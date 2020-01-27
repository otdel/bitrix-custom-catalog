<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:component");

use Bitrix\Main\Application;
use Bitrix\Main\DB\SqlQueryException;

use Oip\SocialStore\Cart\Handler as Cart;
use Oip\SocialStore\User\Entity\User as CartUser;
use Oip\SocialStore\Cart\Repository\RepositoryInterface;
use Oip\SocialStore\Cart\Repository\DBRepository as CartRepository;

use Oip\Util\Collection\Factory\CollectionsFactory as ProductsFactory;
use Oip\Util\Collection\Factory\InvalidSubclass as InvalidSubclassException;
use Oip\Util\Collection\Factory\NonUniqueIdCreating as NonUniqueIdCreatingException;

use Oip\GuestUser\Handler as GuestUser;

abstract class COipSocialStoreCart extends \COipComponent {

    /**
     * @return Cart
     * @throws InvalidSubclassException
     * @throws NonUniqueIdCreatingException
     */
    public function executeComponent()
    {
        return $this->getCart();
    }

    /**
     * @return Cart
     * @throws InvalidSubclassException
     * @throws NonUniqueIdCreatingException
     */
    protected function getCart() {

        try {
            $repository = $this->initCartRepository();
            $user = $this->initCartUser();
            $cart = $this->initCart($user, $repository);
            $cart->getProducts();

            return $cart;
        }
        catch(SqlQueryException $exception) {
            global $APPLICATION;
            $APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                "EXCEPTION" => $exception
            ]);
        }

    }

    /** @return RepositoryInterface */
    private function initCartRepository(): RepositoryInterface {
        $connection = Application::getConnection();
        return new CartRepository($connection);
    }

    /** @return CartUser */
    private function initCartUser(): CartUser {

        global $USER;
        global $OipGuestUser;

        if($USER->IsAuthorized()) {
            $userId = $USER->GetID();
        }
        else {
            /** @var $OipGuestUser GuestUser */

            try {
                $userId = $OipGuestUser->getUser()->getNegativeId();
            }
            catch(SqlQueryException $exception) {
                global $APPLICATION;
                $APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                    "EXCEPTION" => $exception
                ]);
            }

        }

        return new CartUser((int)$userId);
    }

    /**
     * @param CartUser $user
     * @param RepositoryInterface $repository
     * @return Cart
     *
     * @throws InvalidSubclassException
     * @throws NonUniqueIdCreatingException
     */
    private function initCart(CartUser $user, RepositoryInterface $repository): Cart {
        $products = ProductsFactory::createByObjects([], "Oip\SocialStore\Product\Entity\ProductCollection");
        return new Cart($user, $products, $repository);
    }

    /**
     * @return Cart|null
    */
    protected function getProcessorResult(): ?Cart {
        global $OipSocialStoreCart;

        if(!is_set($OipSocialStoreCart) || !($OipSocialStoreCart instanceof Cart)) {
            $this->arResult["EXCEPTION"] = "The component oip:social.store.cart.processor wasn't running";
            return null;
        }
        else {
            return $OipSocialStoreCart;
        }
    }

}