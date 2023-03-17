<?php

use Civi\WebhookAdmin\HeadlessTestCase;

/**
 * @group headless
 */
class CRM_Webhook_Form_WebhookSettingsTest extends HeadlessTestCase
{
    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testBuildQuickForm()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookSettings();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        self::assertEquals('Webhook Settings', $form->getTitle(), 'Invalid form title.');
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testPostProcess()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookSettings();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testLinks()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookSettings();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            $list = $form->links();
            self::assertEquals(2, count($list), 'Invalid link length.');
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
    }
}
