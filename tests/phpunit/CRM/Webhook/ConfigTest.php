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
        self::assertTrue(array_key_exists("sequence", $cfg), "sequence key is missing from the config.");
        self::assertEquals(1, $cfg["sequence"], "Invalid sequence initial value.");
        self::assertTrue(array_key_exists("logs", $cfg), "logs key is missing from the config.");
        self::assertEquals([], $cfg["logs"], "Invalid logs initial value.");
        self::assertTrue(array_key_exists("webhooks", $cfg), "webhooks key is missing from the config.");
        self::assertEquals(1, count($cfg["webhooks"]), "Invalid initial number of webhooks.");
        self::assertTrue(array_key_exists("name", $cfg["webhooks"][0]), "webhooks[0].name key is missing from the config.");
        self::assertEquals($config::DEFAULT_HOOK_NAME, $cfg["webhooks"][0]["name"], "Invalid webhooks[0].name.");
        self::assertTrue(array_key_exists("description", $cfg["webhooks"][0]), "webhooks[0].description key is missing from the config.");
        self::assertEquals($config::DEFAULT_HOOK_DESC, $cfg["webhooks"][0]["description"], "Invalid webhooks[0].description.");
        self::assertTrue(array_key_exists("handler", $cfg["webhooks"][0]), "webhooks[0].handler key is missing from the config.");
        self::assertEquals($config::DEFAULT_HOOK_HANDLER, $cfg["webhooks"][0]["handler"], "Invalid webhooks[0].handler.");
        self::assertTrue(array_key_exists("selector", $cfg["webhooks"][0]), "webhooks[0].selector key is missing from the config.");
        self::assertEquals($config::DEFAULT_HOOK_SELECTOR, $cfg["webhooks"][0]["selector"], "Invalid webhooks[0].selector.");
        self::assertTrue(array_key_exists("processor", $cfg["webhooks"][0]), "webhooks[0].processor key is missing from the config.");
        self::assertEquals($config::DEFAULT_HOOK_PROCESSOR, $cfg["webhooks"][0]["processor"], "Invalid webhooks[0].processor.");
        self::assertTrue(array_key_exists("id", $cfg["webhooks"][0]), "webhooks[0].id key is missing from the config.");
        self::assertEquals(0, $cfg["webhooks"][0]["id"], "Invalid webhooks[0].id.");
        self::assertTrue(array_key_exists("options", $cfg["webhooks"][0]), "webhooks[0].options key is missing from the config.");
        self::assertEquals([], $cfg["webhooks"][0]["options"], "Invalid webhooks[0].options.");
    }
}
