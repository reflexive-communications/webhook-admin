<?php

use CRM_Webhook_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * FIXME - Add test description.
 *
 * @group headless
 */
class CRM_Webhook_Processor_BaseHeadlessTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface, TransactionalInterface
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
     * Output test case.
     */
    public function testOutput()
    {
        $stub = $this->getMockForAbstractClass('CRM_Webhook_Processor_Base');
        $testData = ["key"=>"value"];
        self::expectException(CRM_Core_Exception_PrematureExitException::class, "Invalid exception class.");
        self::assertEmpty($stub->output($testData), "Output supposed to be empty.");
    }
    /**
     * Error test case.
     */
    public function testErrorWithOutput()
    {
        self::markTestIncomplete("This test has not been implemented yet.");
    }
    public function testErrorWithoutOutput()
    {
        self::markTestIncomplete("This test has not been implemented yet.");
    }
}
