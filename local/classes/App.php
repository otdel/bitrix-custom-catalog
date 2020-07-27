<?php

namespace Oip;

use Oip\SocialStore\Product\Price\PriceProviderInterface;
use Oip\SocialStore\Product\Price\StubPriceProvider;
use Exception;

class App
{
    const HTTP_CONTEXT = "HTTP";
    const CLI_CONTEXT = "CLI";

    /** @var array $services */
    private static $services;

    public  static function init() {
        self::initServices();
        self::includeEventHandlers();
    }

    private static function initServices() {

        $defaultServices = [
            PriceProviderInterface::class => function() {
                return new StubPriceProvider([]);
            }
        ];

        $servicesConfig = $_SERVER["DOCUMENT_ROOT"] . '/bitrix/.oip.custom.config.php';
        $customServices = [];
        if ($servicesConfig && is_file($servicesConfig)) {
            $customServices = include($servicesConfig);
        }

        self::$services = $defaultServices;
        foreach ($customServices as $interfaceName => $serviceProvider) {
            self::$services[$interfaceName] = $serviceProvider;
        }
    }

    private static function includeEventHandlers() {

        switch (self::getContext()) {
            case self::HTTP_CONTEXT:
                require_once($_SERVER["DOCUMENT_ROOT"]."/local/include/event_handlers.php");
            break;
        }

    }

    /**
     * @see getService
     * @throws Exception
     *
     */
    public static function getPriceProvider() {
        return self::getService("Oip\SocialStore\Product\Price\PriceProviderInterface");
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public static function getService($name)
    {
        if (array_key_exists($name, self::$services)) {
            $provider = self::$services[$name];
            return call_user_func($provider);
        } else {
            throw new \Exception("Service not found by name $name");
        }
    }

    public static function getContext() {
        return (is_null($_SERVER["HTTP_HOST"])) ? self::CLI_CONTEXT : self::HTTP_CONTEXT;
    }
}