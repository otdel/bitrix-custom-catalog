<?php


namespace Oip\Util\Collection\Factory;

use Oip\Util\Collection\Collection;

class CollectionsFactory
{
    /**
     * @param array $objects
     * @param string $collectionClass
     * @return  Collection
     * 
     * @throws InvalidSubclass
     * @throws NonUniqueIdCreating
    */
    public static function createByObjects(array $objects, string $collectionClass): Collection {
        $ids = [];

        foreach($objects as $object) {

            if(!is_subclass_of($collectionClass, "Oip\Util\Collection\Collection")) {
                throw new InvalidSubclass($collectionClass);
            }

            if(in_array($object->getId(), $ids)) {
                throw new NonUniqueIdCreating($object->getId(), $collectionClass);
            }

            $ids[] =  $object->getId();
        }

        return new $collectionClass($objects);
    }
}