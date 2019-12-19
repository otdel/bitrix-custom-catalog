<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:component");

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;

use Oip\SocialStore\Cart\Handler as Cart;
use Oip\SocialStore\User\Entity\User as CartUser;
use Oip\SocialStore\Cart\Repository\RepositoryInterface;
use Oip\SocialStore\Cart\Repository\DBRepository as CartRepository;
use Oip\SocialStore\Product\Factory\ProductCollection as ProductsFactory;

use Oip\GuestUser\Handler as GuestUser;
use Oip\GuestUser\Repository\CookieRepository as GuestUserRepository;
use Oip\GuestUser\IdGenerator\DBIdGenerator;

class COipSocialStoreCartPage extends \COipComponent {

    public function executeComponent()
    {
        $repository = $this->initCartRepository();
        $user = $this->initCartUser();
        $cart = $this->initCart($user, $repository);

        $cart = $this->handleActions($cart);
        $cart->getProducts();

        $this->arResult["CART"] = $cart;
        $this->includeComponentTemplate();
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

    /*
     * @param CartUser $user
     * @param RepositoryInterface $repository
     * @return Cart
     *
     * */
    private function initCart(CartUser $user, RepositoryInterface $repository): Cart {
        $products = ProductsFactory::createByObjects([]);
        return new Cart($user, $products, $repository);
    }

    /**
     * @param Cart $cart
     * @return Cart
     */
    private function handleActions(Cart $cart): Cart {
        $action = Application::getInstance()->getContext()->getRequest()->getPost(Cart::GLOBAL_CART_ACTION_NAME);

        if(is_set($action)) {
            switch ($action) {

                case Cart::GLOBAL_CART_ACTION_REMOVE_PRODUCT:
                    $productId = (int)Application::getInstance()->getContext()
                        ->getRequest()->getPost(Cart::GLOBAL_CART_DATA_PRODUCT_ID);

                    return $cart->removeProduct($productId);
                break;

                case Cart::GLOBAL_CART_ACTION_REMOVE_ALL:
                    return $cart->removeAll();
                break;
            }
        }

        return $cart;
    }

    /** @return GuestUser */
    private function initGuestUser(): GuestUser {
        $cookieName = Configuration::getValue("oip_guest_user")["cookieName"];
        $cookieExpired = Configuration::getValue("oip_guest_user")["cookieExpired"];

        $siteName = Application::getInstance()->getContext()->getServer()->getServerName();

        $ds = GuestUser::getIDGenDataSource();

        $repository = new GuestUserRepository($cookieName, $cookieExpired, $siteName);
        $idGenerator = new DBIdGenerator($ds);

        return new GuestUser($repository, $idGenerator);
    }
}