<?php

namespace Oip\ApiService\Response;

class Status
{
    /** @var array $value */
    private static $enum = [
        "success" => "success",
        "error" => "error",
    ];

    /** @var string $value */
    private $value;

    private function __construct(string $status) {
        $this->value = $status;
    }

    public static function createSuccess(): self {
        return new self(self::$enum["success"]);
    }

    public static function createError(): self {
        return new self(self::$enum["error"]);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}