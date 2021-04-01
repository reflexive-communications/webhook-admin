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
class CRM_Webhook_ConfigHeadlessTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface, TransactionalInterface
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

    private function isDefaultConfiguration(array $cfg)
    {
        self::assertTrue(array_key_exists("sequence", $cfg), "sequence key is missing from the config.");
        self::assertEquals(1, $cfg["sequence"], "Invalid sequence initial value.");
        self::assertTrue(array_key_exists("logs", $cfg), "logs key is missing from the config.");
        self::assertEquals([], $cfg["logs"], "Invalid logs initial value.");
        self::assertTrue(array_key_exists("webhooks", $cfg), "webhooks key is missing from the config.");
        self::assertEquals(1, count($cfg["webhooks"]), "Invalid initial number of webhooks.");
        self::assertTrue(array_key_exists("name", $cfg["webhooks"][0]), "webhooks[0].name key is missing from the config.");
        self::assertEquals(CRM_Webhook_Config::DEFAULT_HOOK_NAME, $cfg["webhooks"][0]["name"], "Invalid webhooks[0].name.");
        self::assertTrue(array_key_exists("description", $cfg["webhooks"][0]), "webhooks[0].description key is missing from the config.");
        self::assertEquals(CRM_Webhook_Config::DEFAULT_HOOK_DESC, $cfg["webhooks"][0]["description"], "Invalid webhooks[0].description.");
        self::assertTrue(array_key_exists("handler", $cfg["webhooks"][0]), "webhooks[0].handler key is missing from the config.");
        self::assertEquals(CRM_Webhook_Config::DEFAULT_HOOK_HANDLER, $cfg["webhooks"][0]["handler"], "Invalid webhooks[0].handler.");
        self::assertTrue(array_key_exists("selector", $cfg["webhooks"][0]), "webhooks[0].selector key is missing from the config.");
        self::assertEquals(CRM_Webhook_Config::DEFAULT_HOOK_SELECTOR, $cfg["webhooks"][0]["selector"], "Invalid webhooks[0].selector.");
        self::assertTrue(array_key_exists("processor", $cfg["webhooks"][0]), "webhooks[0].processor key is missing from the config.");
        self::assertEquals(CRM_Webhook_Config::DEFAULT_HOOK_PROCESSOR, $cfg["webhooks"][0]["processor"], "Invalid webhooks[0].processor.");
        self::assertTrue(array_key_exists("id", $cfg["webhooks"][0]), "webhooks[0].id key is missing from the config.");
        self::assertEquals(0, $cfg["webhooks"][0]["id"], "Invalid webhooks[0].id.");
        self::assertTrue(array_key_exists("options", $cfg["webhooks"][0]), "webhooks[0].options key is missing from the config.");
        self::assertEquals([], $cfg["webhooks"][0]["options"], "Invalid webhooks[0].options.");
    }
    /**
     * It checks that the create function works well.
     */
    public function testCreate()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        $cfg = $config->get();
        $this->isDefaultConfiguration($cfg);
    }

    /**
     * It checks that the remove function works well.
     */
    public function testRemove()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        self::assertTrue($config->remove(), "Remove config has to be successful.");
    }

    /**
     * It checks that the get function works well.
     */
    public function testGet()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        $config = new CRM_Webhook_Config("webhook_test");
        $cfg = $config->get();
        $this->isDefaultConfiguration($cfg);

        self::assertTrue($config->remove(), "Remove config has to be successful.");
        self::expectException(CRM_Core_Exception::class, "Invalid exception class.");
        self::expectExceptionMessage("webhook_test_configuration config is missing.", "Invalid exception message.");
        $cfg = $config->get();
    }

    /**
     * It checks that the update function works well.
     */
    public function testUpdate()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        $cfg = $config->get();
        $cfg["webhooks"][0]["name"] = "brand new name";
        self::assertTrue($config->update($cfg), "Update config has to be successful.");
        $cfgUpdated = $config->get();
        self::assertEquals($cfg, $cfgUpdated, "Invalid updated configuration.");
    }

    /**
     * It checks that the load function works well.
     */
    public function testLoad()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        $cfg = $config->get();
        $cfg["webhooks"][0]["name"] = "brand new name";
        self::assertTrue($config->update($cfg), "Update config has to be successful.");
        $cfgUpdated = $config->get();
        self::assertEquals($cfg, $cfgUpdated, "Invalid updated configuration.");
        $otherConfig = new CRM_Webhook_Config("webhook_test");
        self::assertEmpty($otherConfig->load(), "Load result supposed to be empty.");

        $cfgLoaded = $otherConfig->get();
        self::assertEquals($cfg, $cfgLoaded, "Invalid loaded configuration.");

        $missingConfig = new CRM_Webhook_Config("webhook_test_missing_config");
        self::expectException(CRM_Core_Exception::class, "Invalid exception class.");
        self::expectExceptionMessage("webhook_test_missing_config_configuration config invalid.", "Invalid exception message.");
        self::assertEmpty($missingConfig->load(), "Load result supposed to be empty.");
    }

    /**
     * It checks that the addWebhook function works well.
     */
    public function testAddWebhookNoDuplication()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        $cfg = $config->get();
        $newHook = [
            "name" => CRM_Webhook_Config::DEFAULT_HOOK_NAME,
            "description" => CRM_Webhook_Config::DEFAULT_HOOK_DESC,
            "handler" => CRM_Webhook_Config::DEFAULT_HOOK_HANDLER,
            "selector" => CRM_Webhook_Config::DEFAULT_HOOK_SELECTOR."_something_different",
            "processor" => CRM_Webhook_Config::DEFAULT_HOOK_PROCESSOR,
            "options" => [],
        ];
        $cfg["webhooks"][1] = $newHook;
        $cfg["webhooks"][1]["id"] = 1;
        $cfg["sequence"] += 1;
        self::assertTrue($config->addWebhook($newHook), "Add Webhook has to be successful.");
        $cfgUpdated = $config->get();
        self::assertEquals($cfg, $cfgUpdated, "Invalid updated configuration.");
    }
    public function testAddWebhookWithDuplication()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        $cfg = $config->get();
        $newHook = [
            "name" => CRM_Webhook_Config::DEFAULT_HOOK_NAME,
            "description" => CRM_Webhook_Config::DEFAULT_HOOK_DESC,
            "handler" => CRM_Webhook_Config::DEFAULT_HOOK_HANDLER,
            "selector" => CRM_Webhook_Config::DEFAULT_HOOK_SELECTOR,
            "processor" => CRM_Webhook_Config::DEFAULT_HOOK_PROCESSOR,
            "options" => [],
        ];
        self::expectException(CRM_Core_Exception::class, "Invalid exception class.");
        self::expectExceptionMessage(CRM_Webhook_Config::DEFAULT_HOOK_SELECTOR." selector is duplicated.", "Invalid exception message.");
        $config->addWebhook($newHook);
    }
    /**
     * It checks that the updateWebhook function works well.
     * Without duplication the update should be successful.
     * On case of duplication exception is expected.
     */
    public function testUpdateWebhookNoDuplication()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        $cfg = $config->get();
        $newHook = [
            "id" => 0,
            "name" => CRM_Webhook_Config::DEFAULT_HOOK_NAME,
            "description" => CRM_Webhook_Config::DEFAULT_HOOK_DESC,
            "handler" => CRM_Webhook_Config::DEFAULT_HOOK_HANDLER,
            "selector" => CRM_Webhook_Config::DEFAULT_HOOK_SELECTOR."_something_different",
            "processor" => CRM_Webhook_Config::DEFAULT_HOOK_PROCESSOR,
            "options" => [],
        ];
        $cfg["webhooks"][0]["selector"] = CRM_Webhook_Config::DEFAULT_HOOK_SELECTOR."_something_different";
        self::assertTrue($config->updateWebhook($newHook), "Update Webhook has to be successful.");
        $cfgUpdated = $config->get();
        self::assertEquals($cfg, $cfgUpdated, "Invalid updated configuration.");
    }
    public function testUpdateWebhookWithDuplication()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        $cfg = $config->get();
        $newHook = [
            "name" => CRM_Webhook_Config::DEFAULT_HOOK_NAME,
            "description" => CRM_Webhook_Config::DEFAULT_HOOK_DESC,
            "handler" => CRM_Webhook_Config::DEFAULT_HOOK_HANDLER,
            "selector" => CRM_Webhook_Config::DEFAULT_HOOK_SELECTOR."_something_different",
            "processor" => CRM_Webhook_Config::DEFAULT_HOOK_PROCESSOR,
            "options" => [],
        ];
        self::assertTrue($config->addWebhook($newHook), "Add Webhook has to be successful.");
        $newHook["id"] = 1;
        $newHook["selector"] = CRM_Webhook_Config::DEFAULT_HOOK_SELECTOR;
        self::expectException(CRM_Core_Exception::class, "Invalid exception class.");
        self::expectExceptionMessage(CRM_Webhook_Config::DEFAULT_HOOK_SELECTOR." selector is duplicated.", "Invalid exception message.");
        $config->updateWebhook($newHook);
    }
    /**
     * It checks that the deleteWebhook function works well.
     */
    public function testDeleteWebhook()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        $cfg = $config->get();
        self::assertEquals(1, count($cfg["webhooks"]), "Invalid default configuration.");
        $config->deleteWebhook(1);
        $cfg = $config->get();
        self::assertEquals(1, count($cfg["webhooks"]), "Invalid number of webhooks after deleting a non existing config.");
        $config->deleteWebhook(0);
        $cfg = $config->get();
        self::assertEquals(0, count($cfg["webhooks"]), "Invalid updated configuration.");
    }
    /**
     * It checks that the insertLog function works well.
     */
    public function testInsertLog()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        $cfg = $config->get();
        self::assertEquals(0, count($cfg["logs"]), "Invalid default configuration.");
        $config->insertLog(["k"=>"v"]);
        $cfg = $config->get();
        self::assertEquals(1, count($cfg["logs"]), "Invalid number of logs after insert.");
        $config->insertLog(["k"=>"v"]);
        $cfg = $config->get();
        self::assertEquals(2, count($cfg["logs"]), "Invalid number of logs after insert.");
    }
    /**
     * It checks that the deleteLogs function works well.
     */
    public function testDeleteLogs()
    {
        $config = new CRM_Webhook_Config("webhook_test");
        self::assertTrue($config->create(), "Create config has to be successful.");
        $cfg = $config->get();
        self::assertEquals(0, count($cfg["logs"]), "Invalid default configuration.");
        $config->insertLog(["k"=>"v"]);
        $cfg = $config->get();
        self::assertEquals(1, count($cfg["logs"]), "Invalid number of logs after insert.");
        $config->deleteLogs();
        $cfg = $config->get();
        self::assertEquals(0, count($cfg["logs"]), "Invalid number of logs after deletion.");
    }
}
