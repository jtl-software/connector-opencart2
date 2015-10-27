<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\Core\Controller\Controller;
use jtl\Connector\Core\Model\QueryFilter;
use PHPUnit_Framework_TestCase;

require_once '../database.php';

abstract class AbstractControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Controller
     */
    protected $controller;

    protected function setUp()
    {
        parent::setUp();
        $this->controller = $this->getController();
    }

    abstract protected function getController();

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testPush()
    {

    }

    public function testPull()
    {
        $result = $this->controller->pull(new QueryFilter());
    }
}