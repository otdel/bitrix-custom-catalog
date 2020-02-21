<?php

namespace Oip\ApiService\Response;

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

    /**
     * Response constructor.
     * @param string $status Статус
     * @param mixed|null $data Набор данных
     * @param string|null $message Техническое сообщение
     * @throws \Exception
     */
    public function __construct($status, $data = null, $message = null)
    {
        $this->setStatus($status);
        $this->setData($data);
        $this->setMessage($message);
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
     * @param mixed $status
     * @return Response
     * @throws \Exception
     */
    public function setStatus($status)
    {
        if (!in_array($status, self::RESPONSE_STATUSES)) {
            throw new \Exception("Некорректный статус для ответа API");
        }
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return Response
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return Response
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Так как фронт будет ждать валидный json в качестве ответа,нельзя выкидывать обычное исключение.
     * Данная функция - замена throw Exception, чтобы прервать выполнение скрипта и отдать
     * ошибку в формате json во время выполнения.
     * @param $message
     */
    protected function throwError($message) {
        die(json_encode([
            "status" => "error",
            "message" => $message
        ]));
    }

}