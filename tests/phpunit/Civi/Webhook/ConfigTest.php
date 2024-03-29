<?php

namespace Civi\Webhook;

use CRM_Core_Exception;

/**
 * @group headless
 */
class ConfigTest extends HeadlessTestCase
{
    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testConfigAfterConstructor()
    {
        $config = new Config('webhook_test');
        $cfg = $config->get();
        self::assertTrue(array_key_exists('logs', $cfg), 'logs key is missing from the config.');
        self::assertSame([], $cfg['logs'], 'Invalid logs initial value.');
    }

    /**
     * @param array $cfg
     *
     * @return void
     */
    private function isDefaultConfiguration(array $cfg)
    {
        self::assertTrue(array_key_exists('logs', $cfg), 'logs key is missing from the config.');
        self::assertSame([], $cfg['logs'], 'Invalid logs initial value.');
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testCreate()
    {
        $config = new Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        $cfg = $config->get();
        $this->isDefaultConfiguration($cfg);
    }

    /**
     * @return void
     */
    public function testRemove()
    {
        $config = new Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        self::assertTrue($config->remove(), 'Remove config has to be successful.');
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testGet()
    {
        $config = new Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        $config = new Config('webhook_test');
        $cfg = $config->get();
        $this->isDefaultConfiguration($cfg);

        self::assertTrue($config->remove(), 'Remove config has to be successful.');
        self::expectException(CRM_Core_Exception::class);
        self::expectExceptionMessage('webhook_test_config config is missing.');
        $cfg = $config->get();
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testUpdate()
    {
        $config = new Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        $cfg = $config->get();
        $cfg['logs'][] = ['key' => 'brand new something'];
        self::assertTrue($config->update($cfg), 'Update config has to be successful.');
        $cfgUpdated = $config->get();
        self::assertEquals($cfg, $cfgUpdated, 'Invalid updated configuration.');
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testLoad()
    {
        $config = new Config('webhook_test');
        self::assertTrue($config->create(), 'Create config has to be successful.');
        $cfg = $config->get();
        $cfg['logs'][] = ['key' => 'brand new something'];
        self::assertTrue($config->update($cfg), 'Update config has to be successful.');
        $cfgUpdated = $config->get();
        self::assertEquals($cfg, $cfgUpdated, 'Invalid updated configuration.');
        $otherConfig = new Config('webhook_test');
        self::assertEmpty($otherConfig->load(), 'Load result supposed to be empty.');

        $cfgLoaded = $otherConfig->get();
        self::assertEquals($cfg, $cfgLoaded, 'Invalid loaded configuration.');

        $missingConfig = new Config('webhook_test_missing_config');
        self::expectException(CRM_Core_Exception::class);
        self::expectExceptionMessage('webhook_test_missing_config_config config invalid.');
        self::assertEmpty($missingConfig->load(), 'Load result supposed to be empty.');
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testInsertLog()
    {
        $config = new Config('webhook_test');
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
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testDeleteLogs()
    {
        $config = new Config('webhook_test');
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
