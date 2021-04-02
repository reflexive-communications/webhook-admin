<?php

use CRM_Webhook_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Logger handler tests.
 *
 * @group headless
 */
class CRM_Webhook_Handler_LoggerHeadlessTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface, TransactionalInterface
{

    public function setUpHeadless()
    {
        return \Civi\Test::headless()
            ->installMe(__DIR__)
            ->apply();
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Handle test case.
     *
     * It shouldn't throw exception.
     */
    public function testHandle()
    {
        $processor = new CRM_Webhook_Processor_Dummy();
        $handler = new CRM_Webhook_Handler_Logger($processor);
        try {
            self::assertEmpty($handler->handle(), "It should be empty.");
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception. ".$e->getMessage());
        }
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->load();
        self::assertEquals(1, count($config->get()["logs"]), "Invalid number of log entries.");
    }
}
