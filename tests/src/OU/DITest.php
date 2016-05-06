<?php

namespace OU;

class DITest extends \PHPUnit_Framework_TestCase
{
    public function testIsShared()
    {
        $di = new DI();
        $di->setShared('date', function (DI $di) {
            return uniqid();
        });
        $di->set('time', function (DI $di) {
            return time();
        });
        $this->assertTrue($di->isShared('date'));
        $this->assertFalse($di->isShared('time'));
    }

    public function testHasKey()
    {
        $di = new DI();
        $di->setShared('date', function (DI $di) {
            return uniqid();
        });
        $di->set('time', function (DI $di) {
            return time();
        });
        $this->assertTrue($di->hasKey('date'));
        $this->assertTrue($di->hasKey('time'));
        $this->assertFalse($di->hasKey('a_object'));
    }

    public function testKeyNotFound()
    {
        $di = new DI();
        $this->setExpectedException('\Exception', 'Key (logger) not defined for DI.');
        $di->get('logger');
    }

    public function testSetSharedWithFunction()
    {
        $di = new DI();
        $di->setShared('date', function () {
            return uniqid();
        });

        $this->assertEquals($di->get('date'), $di->get('date'));
    }

    public function testReloadShared()
    {
        $di = new DI();
        $di->setShared('date', function () {
            return uniqid();
        });

        $this->assertEquals($di->get('date'), $di->get('date'));
        $this->assertNotEquals($di->get('date'), $di->get('date', true));
    }

    public function testSharedServiceReloadShared()
    {
        $di = new DI();
        $di->setSharedService('fake_service', 'OU\UniqidService');
        $this->assertEquals($di->get('fake_service'), $di->get('fake_service'));
        $this->assertNotEquals($di->get('fake_service'), $di->get('fake_service', true));
    }

    public function testService()
    {
        $di = new DI();
        $di->setService('fake_service', 'OU\UniqidService');
        $this->assertNotEquals($di->get('fake_service'), $di->get('fake_service'));
    }
}
