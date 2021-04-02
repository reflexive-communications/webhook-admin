<?php

use CRM_Webhook_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Tests for the install, uninstall process.
 *
 * @group headless
 */
class CRM_Webhook_UpgraderHeadlessTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface
{

    public function setUpHeadless()
    {
        return \Civi\Test::headless()
            ->installMe(__DIR__)
            ->apply();
    }

    public function setUp():void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Create a clean DB before running tests
     *
     * @throws CRM_Extension_Exception_ParseException
     */
    public static function tearDownAfterClass(): void
    {
        \Civi\Test::headless()
            ->uninstallMe(__DIR__)
            ->apply(true);
    }

    /**
     * Test the install process.
     */
    public function testInstall()
    {
        $installer = new CRM_Webhook_Upgrader("webhook_test", ".");
        try {
            $this->assertEmpty($installer->install());
        } catch (Exception $e) {
            $this->fail("Should not throw exception. ".$e->getMessage());
        }
    }

    /**
     * Test the uninstall process.
     */
    public function testUninstall()
    {
        $installer = new CRM_Webhook_Upgrader("webhook_test", ".");
        $this->assertEmpty($installer->install());
        try {
            $this->assertEmpty($installer->uninstall());
        } catch (Exception $e) {
            $this->fail("Should not throw exception. ".$e->getMessage());
        }
    }

    /**
     * Test the upgrade process.
     */
    public function testUpgrade_5000FreshInstall()
    {
        $installer = new CRM_Webhook_Upgrader("webhook_test", ".");
        $this->assertEmpty($installer->install());
        try {
            $this->assertTrue($installer->upgrade_5000());
        } catch (Exception $e) {
            $this->fail("Should not throw exception. ".$e->getMessage());
        }
    }
    public function testUpgrade_5000HasPreviousInstall()
    {
        $origConfig = [
            "sequence" => 2,
            "logs" => [],
            "webhooks" => [
                0 => [
                    "name" => "orig 1",
                    "description" => "orig 1 desc",
                    "handler" => "orig-handler-class-1",
                    "selector" => "orig-selector-1",
                    "processor" => "orig-processor-class-1",
                    "options" => [],
                    "id" => 0,
                ],
                1 => [
                    "name" => "orig 2",
                    "description" => "orig 2 desc",
                    "handler" => "orig-handler-class-2",
                    "selector" => "orig-selector-2",
                    "processor" => "orig-processor-class-2",
                    "options" => [],
                    "id" => 1,
                ],
            ],
        ];
        Civi::settings()->add(["webhook_test_configuration" => $origConfig]);
        $installer = new CRM_Webhook_Upgrader("webhook_test", ".");
        $this->assertEmpty($installer->install());
        try {
            $this->assertTrue($installer->upgrade_5000());
        } catch (Exception $e) {
            $this->fail("Should not throw exception. ".$e->getMessage());
        }
        $newConfig = Civi::settings()->get("webhook_test_config");
        $this->assertEquals($origConfig, $newConfig, "Config has to be the same after the migration.");
        $this->assertNull(Civi::settings()->get("webhook_test_configuration"), "The orig config has to be removed.");
    }
}
