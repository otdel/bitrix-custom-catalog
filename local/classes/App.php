<?php


namespace Oip;


class App
{
    const HTTP_CONTEXT = "HTTP";
    const CLI_CONTEXT = "CLI";

    public  static function init() {
        self::includeEventHandlers();
    }

    private static function includeEventHandlers() {

        switch (self::getContext()) {
            case self::HTTP_CONTEXT:
                require_once($_SERVER["DOCUMENT_ROOT"]."/local/include/event_handlers.php");
            break;
        }

    }

    public static function getContext() {
        return (is_null($_SERVER["HTTP_HOST"])) ? self::CLI_CONTEXT : self::HTTP_CONTEXT;
    }
}