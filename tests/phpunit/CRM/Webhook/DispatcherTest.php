<?php

use Civi\Api4\Webhook;
use Civi\WebhookAdmin\HeadlessTestCase;

/**
 * @group headless
 */
class CRM_Webhook_DispatcherTest extends HeadlessTestCase
{
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
        Webhook::create(false)
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
        Webhook::create(false)
            ->addValue('query_string', 'valid_listener_with_options')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'CRM_Webhook_Handler_Logger')
            ->addValue('processor', 'CRM_Webhook_Processor_Dummy')
            ->addValue('options', ['k' => 'v'])
            ->execute();
        $_GET["listener"] = "valid_listener_with_options";
        $d = new CRM_Webhook_Dispatcher();
        try {
            self::assertEmpty($d->run(), "Run supposed to be empty.");
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception.");
        }
    }
}
