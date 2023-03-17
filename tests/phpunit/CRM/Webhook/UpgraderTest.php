<?php

use Civi\Api4\Webhook;
use Civi\WebhookAdmin\HeadlessTestCase;

/**
 * @group headless
 */
class CRM_Webhook_UpgraderTest extends HeadlessTestCase
{
    /**
     * @return void
     */
    public function testInstall()
    {
        $installer = new CRM_Webhook_Upgrader();
        try {
            $this->assertEmpty($installer->install());
        } catch (Exception $e) {
            $this->fail('Should not throw exception. '.$e->getMessage());
        }
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testUninstall()
    {
        $installer = new CRM_Webhook_Upgrader();
        $this->assertEmpty($installer->install());
        try {
            $this->assertEmpty($installer->uninstall());
        } catch (Exception $e) {
            $this->fail('Should not throw exception. '.$e->getMessage());
        }
    }

    /**
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testPostInstall()
    {
        $installer = new CRM_Webhook_Upgrader();
        // delete the first id as it is the one that was inserted.
        Webhook::delete(false)
            ->addWhere('id', '=', 1)
            ->execute();
        $currentNumber = Webhook::get(false)
            ->selectRowCount()
            ->execute();
        $this->assertEmpty($installer->postInstall());
        $newNumber = Webhook::get(false)
            ->selectRowCount()
            ->execute();
        self::assertSame(count($currentNumber) + 1, count($newNumber));
    }
}
