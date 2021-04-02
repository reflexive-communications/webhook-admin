<?php

/**
 * WebhookSettings tests
 *
 * @group headless
 */
class CRM_Webhook_Form_WebhookSettingsTest extends CRM_Webhook_Form_TestBase
{

    /**
     * Build quick form test case.
     * Setup test configuration, preProcess then call the function.
     * It shouldn't throw exception.
     * The title should be set.
     */
    public function testBuildQuickForm()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookSettings();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        self::assertEquals("Webhook Settings", $form->getTitle(), "Invalid form title.");
    }

    /**
     * Post Process test case. It does nothing.
     */
    public function testPostProcess()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookSettings();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
    }

    /**
     * Links test case.
     * It shouldn't throw exception.
     */
    public function testLinks()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookSettings();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        try {
            $list = $form->links();
            self::assertEquals(2, count($list), "Invalid link length.");
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
    }
}
