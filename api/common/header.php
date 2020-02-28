<?php
$_SERVER["DOCUMENT_ROOT"] = __DIR__ . "/../..";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

require $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";

set_exception_handler("Oip\ApiService\ExceptionHandler\ExceptionHandler::throwJsonException");