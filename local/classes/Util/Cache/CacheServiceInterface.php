<?php

namespace Oip\Util\Cache;


interface CacheServiceInterface
{
    /**
     * Получение данных из кеша.
     * В случае, если кеш истек (с момента записи кеша прошло более $cacheTime секунд), вызывается функция $function и
     * ее результат записывается в кеш и отдается в качестве результирующего набора данных.
     * @param string $cacheKey Ключ кеша
     * @param int $cacheTime Время жизни кеша, в секундах
     * @param string $cacheVariable Название переменной, записываемой/получаемой из кеша
     * @param callable $function Функция, вызываемая для получения данных в случае истечения кеша
     * @return mixed
     */
    public static function getCacheVariable(
        string $cacheKey,
        int $cacheTime,
        string $cacheVariable,
        callable $function
    );
}