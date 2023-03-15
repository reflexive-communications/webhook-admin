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

    private function isDefaultConfiguration(array $cfg)
    {
        self::assertTrue(array_key_exists('logs', $cfg), 'logs key is missing from the config.');
        self::assertSame([], $cfg['logs'], 'Invalid logs initial value.');
    }

    /**
     * It checks that the create function works well.
     */
    public function testCreate()
    {
        $config = new CRM_Webhook_Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        $cfg = $config->get();
        $this->isDefaultConfiguration($cfg);
    }

    /**
     * It checks that the remove function works well.
     */
    public function testRemove()
    {
        $config = new CRM_Webhook_Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        self::assertTrue($config->remove(), 'Remove config has to be successful.');
    }

    /**
     * It checks that the get function works well.
     */
    public function testGet()
    {
        $config = new CRM_Webhook_Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        $config = new CRM_Webhook_Config('webhook_test');
        $cfg = $config->get();
        $this->isDefaultConfiguration($cfg);

        self::assertTrue($config->remove(), 'Remove config has to be successful.');
        self::expectException(CRM_Core_Exception::class);
        self::expectExceptionMessage('webhook_test_config config is missing.');
        $cfg = $config->get();
    }

    /**
     * It checks that the update function works well.
     */
    public function testUpdate()
    {
        $config = new CRM_Webhook_Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        $cfg = $config->get();
        $cfg['logs'][] = ['key' => 'brand new something'];
        self::assertTrue($config->update($cfg), 'Update config has to be successful.');
        $cfgUpdated = $config->get();
        self::assertEquals($cfg, $cfgUpdated, 'Invalid updated configuration.');
    }

    /**
     * It checks that the load function works well.
     */
    public function testLoad()
    {
        $config = new CRM_Webhook_Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        $cfg = $config->get();
        $cfg['logs'][] = ['key' => 'brand new something'];
        self::assertTrue($config->update($cfg), 'Update config has to be successful.');
        $cfgUpdated = $config->get();
        self::assertEquals($cfg, $cfgUpdated, 'Invalid updated configuration.');
        $otherConfig = new CRM_Webhook_Config('webhook_test');
        self::assertEmpty($otherConfig->load(), 'Load result supposed to be empty.');

        $cfgLoaded = $otherConfig->get();
        self::assertEquals($cfg, $cfgLoaded, 'Invalid loaded configuration.');

        $missingConfig = new CRM_Webhook_Config('webhook_test_missing_config');
        self::expectException(CRM_Core_Exception::class);
        self::expectExceptionMessage('webhook_test_missing_config_config config invalid.');
        self::assertEmpty($missingConfig->load(), 'Load result supposed to be empty.');
    }

    /**
     * It checks that the insertLog function works well.
     */
    public function testInsertLog()
    {
        $config = new CRM_Webhook_Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        $cfg = $config->get();
        self::assertEquals(0, count($cfg['logs']), 'Invalid default configuration.');
        $config->insertLog(['k' => 'v']);
        $cfg = $config->get();
        self::assertEquals(1, count($cfg['logs']), 'Invalid number of logs after insert.');
        $config->insertLog(['k' => 'v']);
        $cfg = $config->get();
        self::assertEquals(2, count($cfg['logs']), 'Invalid number of logs after insert.');
    }

    /**
     * It checks that the deleteLogs function works well.
     */
    public function testDeleteLogs()
    {
        $config = new CRM_Webhook_Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        $cfg = $config->get();
        self::assertEquals(0, count($cfg['logs']), 'Invalid default configuration.');
        $config->insertLog(['k' => 'v']);
        $cfg = $config->get();
        self::assertEquals(1, count($cfg['logs']), 'Invalid number of logs after insert.');
        $config->deleteLogs();
        $cfg = $config->get();
        self::assertEquals(0, count($cfg['logs']), 'Invalid number of logs after deletion.');
    }
}
