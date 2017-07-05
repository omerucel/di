<?php

namespace OU;

use OU\Di\ContainerExceptionImp;
use OU\Di\NotFoundExceptionImp;
use Psr\Container\ContainerInterface;

class DI implements ContainerInterface
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
     * @return mixed
     * @throws \Exception
     */
    public function get($key)
    {
        if (!isset($this->objects[$key])) {
            throw new NotFoundExceptionImp('Key (' . $key . ') not defined for DI.');
        }
        $objectInfo = $this->objects[$key];
        if (isset($objectInfo['shared_object'])) {
            return $objectInfo['shared_object'];
        }
        return $this->createObject($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function reloadShared($key)
    {
        return $this->createObject($key);
    }

    /**
     * @param $key
     * @return mixed
     * @throws ContainerExceptionImp
     */
    protected function createObject($key)
    {
        $objectInfo = $this->objects[$key];
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
                try {
                    $object = call_user_func_array($object, array($this));
                } catch (\Throwable $exception) {
                    throw new ContainerExceptionImp();
                }
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
    public function has($key)
    {
        return $this->hasKey($key);
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
