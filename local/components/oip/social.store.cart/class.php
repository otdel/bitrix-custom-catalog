<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:component");

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;

use Oip\SocialStore\Cart\Handler as Cart;
use Oip\SocialStore\User\Entity\User as CartUser;
use Oip\SocialStore\Cart\Repository\RepositoryInterface;
use Oip\SocialStore\Cart\Repository\DBRepository as CartRepository;

use Oip\Util\Collection\Factory\CollectionsFactory as ProductsFactory;
use Oip\Util\Collection\Factory\InvalidSubclass as InvalidSubclassException;
use Oip\Util\Collection\Factory\NonUniqueIdCreating as NonUniqueIdCreatingException;

use Oip\GuestUser\Handler as GuestUser;
use Oip\GuestUser\Repository\CookieRepository as GuestUserRepository;
use Oip\GuestUser\IdGenerator\DBIdGenerator;

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
        $repository = $this->initCartRepository();
        $user = $this->initCartUser();
        $cart = $this->initCart($user, $repository);
        $cart->getProducts();

        return $cart;
    }

    /** @return RepositoryInterface */
    private function initCartRepository(): RepositoryInterface {
        $connection = Application::getConnection();
        return new CartRepository($connection);
    }

    /** @return CartUser */
    private function initCartUser(): CartUser {

        /*
         * пока нет функционала слива данных гостя при авторизации,
         * временно возвращается только id гостя и корзина хранится по нему

            global $USER;

            if($USER->IsAuthorized()) {
                $userId = $USER->GetID();
            }
            else {
                $userId = $this->initGuestUser()->getUser()->getId();
            }
        */
        $userId = $this->initGuestUser()->getUser()->getId();

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
     * @return GuestUser
     * @throws
     */
    private function initGuestUser(): GuestUser {
        $cookieName = Configuration::getValue("oip_guest_user")["cookieName"];
        $cookieExpired = Configuration::getValue("oip_guest_user")["cookieExpired"];

        $siteName = Application::getInstance()->getContext()->getServer()->getServerName();

        $ds = GuestUser::getIDGenDataSource();

        $repository = new GuestUserRepository($cookieName, $cookieExpired, $siteName);
        $idGenerator = new DBIdGenerator($ds);

        return new GuestUser($repository, $idGenerator);
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