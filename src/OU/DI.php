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
        $this->objects[$key] = array(
            'is_shared' => true,
            'object' => $object
        );
    }

    public function setSharedService($key, $class)
    {
        $this->objects[$key] = array(
            'is_shared' => true,
            'class' => $class
        );
    }

    /**
     * @param $key
     * @param $object
     */
    public function set($key, $object)
    {
        $this->objects[$key] = array(
            'object' => $object
        );
    }

    /**
     * @param $key
     * @param $class
     */
    public function setService($key, $class)
    {
        $this->objects[$key] = array(
            'class' => $class
        );
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

        if (isset($objectInfo['class'])) {
            /**
             * @var Service $service
             */
            $class = $objectInfo['class'];
            $service = new $class();
            $object = $service->getService($this);
            $this->objects[$key]['object'] = $object;
        } else {
            $object = $objectInfo['object'];
            if (is_callable($object)) {
                $object = call_user_func_array($object, array($this));
            }
        }
        if ($this->isShared($key)) {
            $this->objects[$key]['shared_object'] = $object;
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
