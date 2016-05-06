<?php

namespace OU;

class UniqidService implements Service
{
    /**
     * @param DI $di
     * @return string
     */
    public function getService(DI $di)
    {
        return uniqid();
    }
}
