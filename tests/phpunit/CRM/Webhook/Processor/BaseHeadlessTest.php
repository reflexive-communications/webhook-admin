<?php

use Civi\Test;
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
    /**
     * Apply a forced rebuild of DB, thus
     * create a clean DB before running tests
     *
     * @throws \CRM_Extension_Exception_ParseException
     */
    public static function setUpBeforeClass(): void
    {
        // Resets DB
        Test::headless()
            ->install('rc-base')
            ->installMe(__DIR__)
            ->apply(true);
    }

    public function setUpHeadless()
    {
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
        $testData = ["key" => "value"];
        self::expectException(CRM_Core_Exception_PrematureExitException::class);
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
