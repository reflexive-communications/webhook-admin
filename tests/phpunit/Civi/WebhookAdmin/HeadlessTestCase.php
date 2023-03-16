<?php

namespace Civi\WebhookAdmin;

use Civi\Test;
use Civi\Test\HeadlessInterface;
use CRM_Webhook_Config;
use CRM_Webhook_ExtensionUtil as E;
use PHPUnit\Framework\TestCase;

/**
 * @group headless
 */
class HeadlessTestCase extends TestCase implements HeadlessInterface
{
    public const TEST_SETTINGS = [
        'logs' => [],
    ];

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

    /**
     * @return void
     */
    public function setUpHeadless(): void
    {
    }

    protected function setupTestConfig()
    {
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->create();
        self::assertTrue($config->update(self::TEST_SETTINGS), 'Config update has to be successful.');
    }

    protected function setGlobals(string $key, $value)
    {
        $_GET[$key] = $value;
        $_POST[$key] = $value;
        $_REQUEST[$key] = $value;
    }
}
