<?php

namespace Civi\Webhook\Handler;

use Civi\Webhook\Config;
use Civi\Webhook\HeadlessTestCase;
use Civi\Webhook\Processor\Dummy;
use CRM_Webhook_ExtensionUtil as E;
use Exception;

/**
 * @group headless
 */
class LoggerTest extends HeadlessTestCase
{
    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testHandle()
    {
        $processor = new Dummy();
        $handler = new Logger($processor);
        try {
            self::assertEmpty($handler->handle(), 'It should be empty.');
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception. ".$e->getMessage());
        }
        $config = new Config(E::LONG_NAME);
        $config->load();
        self::assertEquals(1, count($config->get()['logs']), 'Invalid number of log entries.');
    }
}
