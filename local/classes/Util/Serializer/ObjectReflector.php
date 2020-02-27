<?php

namespace Oip\Util\Serializer;

class ObjectReflector
{

    /**
     * Local cache of a property getters per class - optimize reflection code if the same object appears several times
     * @var array
     */
    private $classPropertyGetters = array();

    /**
     * @param mixed $object
     * @return string|false
     */
    public function serialize($object)
    {
        return $this->serializeInternal($object);
    }

    /**
     * @param $object
     * @return array
     */
    private function serializeInternal($object)
    {
        if (is_array($object)) {
            $result = $this->serializeArray($object);
        } elseif (is_object($object)) {
            $result = $this->serializeObject($object);
        } else {
            $result = $object;
        }
        return $result;
    }

    /**
     * @param $object
     * @return \ReflectionClass
     */
    private function getClassPropertyGetters($object)
    {
        $className = get_class($object);
        if (!isset($this->classPropertyGetters[$className])) {
            $reflector = new \ReflectionClass($className);
            $properties = $reflector->getProperties();
            $getters = array();
            foreach ($properties as $property)
            {
                $name = $property->getName();
                $getter = "get" . ucfirst($name);
                try {
                    $reflector->getMethod($getter);
                    $getters[$name] = $getter;
                } catch (\Exception $e) {
                    // Если для свойства не существует геттера - пропускаем его
                }
            }
            $this->classPropertyGetters[$className] = $getters;
        }
        return $this->classPropertyGetters[$className];
    }

    /**
     * @param $object
     * @return array
     */
    private function serializeObject($object) {
        $properties = $this->getClassPropertyGetters($object);
        $data = array();
        foreach ($properties as $name => $property)
        {
            $data[$name] = $this->serializeInternal($object->$property());
        }
        return $data;
    }

    /**
     * @param $array
     * @return array
     */
    private function serializeArray($array)
    {
        $result = array();
        foreach ($array as $key => $value) {
            $result[$key] = $this->serializeInternal($value);
        }
        return $result;
    }

}