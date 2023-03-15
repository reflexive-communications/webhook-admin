<?php

use Civi\WebhookAdmin\HeadlessTestCase;

/**
 * @group headless
 */
class CRM_Webhook_ConfigTest extends HeadlessTestCase
{
    /**
     * Default configuration test case.
     */
    public function testConfigAfterConstructor()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        $cfg = $config->get();
        self::assertTrue(array_key_exists("logs", $cfg), "logs key is missing from the config.");
        self::assertSame([], $cfg["logs"], "Invalid logs initial value.");
    }
}
