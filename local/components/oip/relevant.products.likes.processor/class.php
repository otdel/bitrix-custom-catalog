<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;

CBitrixComponent::includeComponentClass("oip:relevant.products");

class CRelevantProductsLikesProcessor extends \CRelevantProducts
{

    public function executeComponent()
    {
        try {
            $this->handleAction();
        }
        catch (Exception $e) {
            global $APPLICATION;
            $APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                "EXCEPTION" => $e
            ]);
        }

    }


    /**
     * @throws Exception;
     */
    private function handleAction(): void {
        $action = Application::getInstance()->getContext()->getRequest()
            ->getPost($this->dw::GLOBAL_PRODUCT_LIKE_ACTION_NAME);
        $actionProductId = (int)Application::getInstance()->getContext()
            ->getRequest()->getPost($this->dw::GLOBAL_PRODUCT_LIKE_PRODUCT_ID);

        if(is_set($action)) {
            switch ($action) {

                case $this->dw::GLOBAL_PRODUCT_LIKE_ACTION_ADD:
                    $this->dw->addProductLike($this->getUserId(), $actionProductId);
                break;

                case $this->dw::GLOBAL_PRODUCT_LIKE_ACTION_REMOVE:
                    $this->dw->removeProductLike($this->getUserId(), $actionProductId);
                break;
            }
        }

    }
}