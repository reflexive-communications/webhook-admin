<?php

use Civi\Test;
use CRM_Webhook_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Base class for the form tests.
 */
class CRM_Webhook_Form_TestBase extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface, TransactionalInterface
{
    const TEST_SETTINGS = [
        "logs" => [],
    ];

    protected function setGlobals(string $key, $value)
    {
        $_GET[$key] = $value;
        $_POST[$key] = $value;
        $_REQUEST[$key] = $value;
    }

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

    protected function setupTestConfig()
    {
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->create();
        self::assertTrue($config->update(self::TEST_SETTINGS), "Config update has to be successful.");
    }
}
