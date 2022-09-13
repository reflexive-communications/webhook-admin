<?php

/**
 * This is a generic test class for the extension (implemented with PHPUnit).
 */
class CRM_Webhook_ConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * The setup() method is executed before the test is executed (optional).
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * The tearDown() method is executed after the test was executed (optional)
     * This can be used for cleanup.
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Default configuration test case.
     *
     */
    public function testConfigAfterConstructor()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        $cfg = $config->get();
        self::assertTrue(array_key_exists("logs", $cfg), "logs key is missing from the config.");
        self::assertSame([], $cfg["logs"], "Invalid logs initial value.");
    }
}
