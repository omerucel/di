<?php

namespace OU;

interface Service
{
    /**
     * @param DI $di
     * @return mixed
     */
    public function getService(DI $di);
}
