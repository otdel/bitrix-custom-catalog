<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;
use Bitrix\Main\SystemException;

use Oip\SocialStore\Cart\Handler as Cart;

use Oip\SocialStore\Order\Repository\Exception\OrderCreatingError as OrderCreatingErrorException;
use Oip\SocialStore\Cart\Exception\UnableToCreateTheCart as UnableToCreateTheCartException;
use Oip\Util\Collection\Factory\InvalidSubclass as InvalidSubclassException;
use Oip\Util\Collection\Factory\NonUniqueIdCreating as NonUniqueIdCreatingException;

\CBitrixComponent::includeComponentClass("oip:social.store.cart");

class COipSocialStoreProcessor extends \COipSocialStoreCart {

    /**
     * @throws InvalidSubclassException
     * @throws NonUniqueIdCreatingException
     * @throws SystemException
     */
    public function executeComponent()
    {
        $cart = $this->getCart();

        try {
            if(is_null($cart)) {
                throw new UnableToCreateTheCartException();
            }
            else {
                $GLOBALS["OipSocialStoreCart"] =  $this->handleAction($cart);
            }
        }
        catch (UnableToCreateTheCartException $exception) {
            global $APPLICATION;
            $APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                "EXCEPTION" => $exception
            ]);
        }
    }

    /**
     * @param Cart $cart
     * @return Cart
     * @throws SystemException
     */
    protected function handleAction(Cart $cart): Cart {

        $action = Application::getInstance()->getContext()->getRequest()->getPost(Cart::GLOBAL_CART_ACTION_NAME);
        $actionProductId = (int)Application::getInstance()->getContext()
            ->getRequest()->getPost(Cart::GLOBAL_CART_DATA_PRODUCT_ID);

        if(is_set($action)) {
            switch ($action) {

                case Cart::GLOBAL_CART_ACTION_ADD_PRODUCT:

                    return $cart->addProduct($actionProductId);
                    break;

                case Cart::GLOBAL_CART_ACTION_REMOVE_PRODUCT:
                    return $cart->removeProduct($actionProductId);
                    break;

                case Cart::GLOBAL_CART_ACTION_CREATE_ORDER:
                    global $APPLICATION;

                    try {
                        $resultError = $APPLICATION->IncludeComponent("oip:social.store.order.add","",
                            [
                                "USER" => $cart->getUser(),
                                "PRODUCTS" => $cart->getProducts()
                            ]);

                        if(is_null($resultError)) {
                            $cart->removeAll();
                            $GLOBALS["OipSocialStoreCartOrderCreatedSuccess"] = "Your new order was successfully created";
                        }
                        else {
                            $GLOBALS["OipSocialStoreCartOrderCreatingErrors"][] = $resultError;
                        }
                    }
                    catch (OrderCreatingErrorException $exception) {
                        $GLOBALS["OipSocialStoreCartOrderCreatingErrorException"] = $exception->getMessage();
                    }

                    return $cart;

                    break;

                case Cart::GLOBAL_CART_ACTION_REMOVE_ALL:
                    return $cart->removeAll();
                    break;
            }
        }

        return $cart;
    }

}