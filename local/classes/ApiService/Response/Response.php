<?php

namespace Oip\ApiService\Response;

use Oip\Util\Serializer\ObjectReflector;

class Response
{
    /** @var Status $status Статус ответа */
    private $status;
    /** @var string|null $message Техническое сообщение */
    private $message;
    /** @var mixed $data Набор данных */
    private $data;
    /** @var ObjectReflector $serializer */
    private $serializer;

    public function __construct(
        ObjectReflector $serializer,
        Status $status,
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
     * @param ObjectReflector $serializer
     * @param Status $status Статус
     * @param mixed|null $data Набор данных
     * @param string|null $message Техническое сообщение
     * @throws \Exception
     * @return self
     */
    public static function create(
        ObjectReflector $serializer,
        Status $status,
        $data = null,
        string $message = null
    ): self {
        if(is_null($data)) {
            return null;
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
     * @return Status
     */
    public function getStatus(): Status
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