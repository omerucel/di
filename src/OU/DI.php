<?php

namespace OU;

class DI
{
    protected $objects;

    /**
     * @param $key
     * @param $object
     */
    public function setShared($key, $object)
    {
        $this->objects[$key] = [
            'is_shared' => true,
            'object' => $object
        ];
    }

    /**
     * @param $key
     * @param $object
     */
    public function set($key, $object)
    {
        $this->objects[$key] = [
            'object' => $object
        ];
    }

    /**
     * @param $key
     * @param bool $reloadShared
     * @return mixed
     * @throws \Exception
     */
    public function get($key, $reloadShared = false)
    {
        if (!isset($this->objects[$key])) {
            throw new \Exception('Key (' . $key . ') not defined for DI.');
        }
        $objectInfo = $this->objects[$key];
        if ($reloadShared == false && isset($objectInfo['shared_object'])) {
            return $objectInfo['shared_object'];
        }

        $object = $objectInfo['object'];
        if (is_callable($object)) {
            $object = call_user_func_array($object, [$this]);
            if ($this->isShared($key)) {
                $this->objects[$key]['shared_object'] = $object;
            }
        }
        return $object;
    }

    /**
     * @param $key
     * @return bool
     */
    public function isShared($key)
    {
        return $this->hasKey($key) && isset($this->objects[$key]['is_shared']);
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasKey($key)
    {
        return isset($this->objects[$key]);
    }
}
