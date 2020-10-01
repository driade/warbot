<?php declare (strict_types = 1);

namespace WarBot\Tests;

use DI\Container;
use PHPUnit\Framework\TestCase;

class WarBotTestCase extends TestCase
{
    /** @var Container */
    protected $container;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = new Container();
    }

    protected function mock($class, $callable)
    {
        $mock = $callable((new \Mockery)->mock($class))->getMock();
        $this->container->set($class, $mock);
        return $mock;
    }
}
