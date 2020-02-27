<?php

namespace Oip\ApiService\Response;

use Oip\Util\Serializer\ObjectReflector;

class Response
{
    /** @var string $status Статус ответа */
    private $status;
    /** @var string|null $message Техническое сообщение */
    private $message;
    /** @var mixed $data Набор данных */
    private $data;

    public function __construct(
        $status,
        string $data = null,
        $message = null
    )
    {
        $this->status = $status;
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * @param ObjectReflector $serializer
     * @param Status $status Статус
     * @param mixed $data Набор данных
     * @return self
     */
    public static function create(
        ObjectReflector $serializer,
        Status $status,
        $data
    ): self {
        $strData = json_encode($serializer->serialize($data));
        return new self($status->getValue(), $strData, null);
    }

    public static function createError(
        Status $status,
        string $message
    ): self {
        return new self($status->getValue(), null, $message);
    }

    /**
     * Формирование json из объекта Response
     */
    public function __toString()
    {
        return json_encode([
            "status" => $this->status,
            "message" => $this->message,
            "data" => $this->data
        ]);
    }

    /**
     * Формирование json из объекта Response
     */
    public function toJSON()
    {
        return json_encode([
            "status" => $this->status,
            "message" => $this->message,
            "data" => $this->data
        ]);
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}