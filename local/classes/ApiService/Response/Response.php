<?php

namespace Oip\ApiService\Response;

use Oip\Util\Serializer\JsonSerializer\JsonSerializer;
use Oip\ApiService\Response\Exception\InvalidResponseStatus as InvalidResponseStatusException;

class Response
{
    /** @var array RESPONSE_STATUSES Возможные статусы */
    private const RESPONSE_STATUSES = ["success", "error"];

    /** @var string $status Статус ответа */
    private $status;
    /** @var string|null $message Техническое сообщение */
    private $message;
    /** @var mixed $data Набор данных */
    private $data;
    /** @var JsonSerializer $serializer */
    private $serializer;

    private function __construct(
        JsonSerializer $serializer,
        string $status,
        string $data = null,
        $message = null
    )
    {
        $this->serializer = $serializer;
        $this->status = $status;
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * @param JsonSerializer $serializer
     * @param string $status Статус
     * @param mixed|null $data Набор данных
     * @param string|null $message Техническое сообщение
     * @throws \Exception
     * @return self
     */
    public static function create(
        JsonSerializer $serializer,
        string $status,
        $data = null,
        string $message = null
    ): self {
        if(is_null($data)) {
            return null;
        }

        if (!in_array($status, self::RESPONSE_STATUSES)) {
            throw new InvalidResponseStatusException($status);
        }

        $strData = json_encode($serializer->serialize($data));
        return new self($serializer, $status, $strData, $message);
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
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getMessage()
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