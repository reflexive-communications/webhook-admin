<?php

namespace Civi\Webhook\Handler;

use Civi\Webhook\HeadlessTestCase;
use Civi\Webhook\Processor\Dummy;
use Exception;

/**
 * @group headless
 */
class BaseTest extends HeadlessTestCase
{
    /**
     * @return void
     */
    public function testHandle()
    {
        $processor = new Dummy();
        $stub = $this->getMockForAbstractClass('Civi\Webhook\Handler\Base', [$processor]);
        $stub->method('authenticate')->willReturn(true);
        $stub->method('validate')->willReturn(true);
        try {
            self::assertEmpty($stub->handle(), 'It should be empty.');
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception. ".$e->getMessage());
        }
    }
}
