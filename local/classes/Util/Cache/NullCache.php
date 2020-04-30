<?php


namespace Oip\Util\Cache;


class NullCache implements CacheServiceInterface
{
    public static function getCacheVariable(string $cacheKey, int $cacheTime, string $cacheVariable, callable $function)
    {
        return $function();
    }

}