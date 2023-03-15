<?php

use Civi\WebhookAdmin\HeadlessTestCase;

/**
 * @group headless
 */
class CRM_Webhook_UpgraderTest extends HeadlessTestCase
{
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
     * Test the post install process.
     */
    public function testPostInstall()
    {
        $installer = new CRM_Webhook_Upgrader("webhook_test", ".");
        // delete the first id as it is the one that was inserted.
        \Civi\Api4\Webhook::delete(false)
            ->addWhere('id', '=', 1)
            ->execute();
        $currentNumber = \Civi\Api4\Webhook::get(false)
            ->selectRowCount()
            ->execute();
        $this->assertEmpty($installer->postInstall());
        $newNumber = \Civi\Api4\Webhook::get(false)
            ->selectRowCount()
            ->execute();
        self::assertSame(count($currentNumber) + 1, count($newNumber));
    }
}
