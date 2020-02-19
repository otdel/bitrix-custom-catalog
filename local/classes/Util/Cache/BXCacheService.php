<?php

namespace Oip\Util\Cache;

use Bitrix\Main\Data\Cache as BitrixCache;

class BXCacheService implements CacheServiceInterface
{
    /**
     * @inheritdoc
     * Реализация CacheServiceInterface для битрикса
     * @see CacheServiceInterface::getCacheVariable
     */
    public static function getCacheVariable(
        string $cacheKey,
        int $cacheTime,
        string $cacheVariable,
        callable $function
    ) {
        // Получаем экземпляр класса Cache
        $cache = BitrixCache::createInstance();
        // Если кеш есть и он включен
        if ($cache->initCache($cacheTime, $cacheKey)) {
            $cacheVariables = $cache->getVars();
            return unserialize($cacheVariables[$cacheVariable]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            // Записываем результат выполнения $function() в переменную кеша $cacheVariable
            $result = $function();
            $cache->endDataCache(array($cacheVariable => serialize($function())));
            return $result;
        }
        else {
            // Если при работе с кешем что-то пошло не так - просто отдаем результат работы функции
            return $function();
        }
    }

}