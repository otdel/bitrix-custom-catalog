<?php

namespace Oip;

/**
 * Class CacheInfo
 * Структура, хранящая необходимую информацию о кешировании внутри компонента
 * @package Oip
 */
class CacheInfo
{
    /** @var bool $isCacheEnabled Флаг - включен ли кеш */
    private $isCacheEnabled;
    /** @var int $cacheLifeTime Время жизни кеша, в секундах */
    private $cacheLifeTime;
    /** @var string $cacheKey Общая часть ключа кеша (префикс) */
    private $cacheKey;

    /**
     * CacheInfo constructor.
     * @param bool $isCacheEnabled
     * @param int $cacheLifeTime
     * @param string $cacheKey
     */
    public function __construct($isCacheEnabled = false, $cacheLifeTime = 300, $cacheKey = "")
    {
        $this->isCacheEnabled = $isCacheEnabled;
        $this->cacheLifeTime = $cacheLifeTime;
        $this->cacheKey = $cacheKey;
    }

    /**
     * @return int
     */
    public function getCacheLifeTime(): int
    {
        return $this->cacheLifeTime;
    }

    /**
     * @return string
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    /**
     * @return bool
     */
    public function isCacheEnabled(): bool
    {
        return $this->isCacheEnabled;
    }

}
