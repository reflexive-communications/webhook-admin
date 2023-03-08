<?php

use Civi\Test;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Tests for dispatcher logic.
 *
 * @group headless
 */
class CRM_Webhook_DispatcherHeadlessTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface, TransactionalInterface
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
     * Run command tests.
     * Without listener get param, it fails.
     * Without valid listener param it fails.
     * With vaild value, it handles the request.
     * Add valid config and test against it.
     */
    public function testRunMissingListener()
    {
        if (isset($_GET["listener"])) {
            unset($_GET["listener"]);
        }
        $d = new CRM_Webhook_Dispatcher();
        self::expectException(Exception::class);
        self::expectExceptionMessage("Missing listener.");
        self::assertEmpty($d->run(), "Run supposed to be empty.");
    }

    public function testRunInvalidListener()
    {
        $_GET["listener"] = "not-existing-listener";
        $d = new CRM_Webhook_Dispatcher();
        self::expectException(Exception::class);
        self::expectExceptionMessage("Invalid listener.");
        self::assertEmpty($d->run(), "Run supposed to be empty.");
    }

    public function testRunValidListener()
    {
        \Civi\Api4\Webhook::create(false)
            ->addValue('query_string', 'valid_listener')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'CRM_Webhook_Handler_Logger')
            ->addValue('processor', 'CRM_Webhook_Processor_Dummy')
            ->execute();
        $_GET["listener"] = "valid_listener";
        $d = new CRM_Webhook_Dispatcher();
        try {
            self::assertEmpty($d->run(), "Run supposed to be empty.");
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception.");
        }
    }

    public function testRunValidListenerWithOptions()
    {
        \Civi\Api4\Webhook::create(false)
            ->addValue('query_string', 'valid_listener')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'CRM_Webhook_Handler_Logger')
            ->addValue('processor', 'CRM_Webhook_Processor_Dummy')
            ->addValue('options', ['k' => 'v'])
            ->execute();
        $_GET["listener"] = "valid_listener";
        $d = new CRM_Webhook_Dispatcher();
        try {
            self::assertEmpty($d->run(), "Run supposed to be empty.");
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception.");
        }
    }
}
