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
class CRM_Webhook_Form_WebhookDeleteTest extends CRM_Webhook_Form_TestBase
{

    /**
     * Build quick form test cases.
     * Setup test configuration, preProcess then call the function.
     * It shouldn't throw exception.
     * The title should be set.
     */
    public function testBuildQuickFormWithId()
    {
        $this->setGlobals("id", self::TEST_SETTINGS["webhooks"][0]["id"]);
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookDelete();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        self::assertEquals("Webhook Delete", $form->getTitle(), "Invalid form title.");
    }

    public function testPostProcessValidId()
    {
        $this->setGlobals("id", self::TEST_SETTINGS["webhooks"][0]["id"]);
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookDelete();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        $config->load();
        self::assertEquals(1, count($config->get()["webhooks"]), "We have only 1 hook");
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        $config->load();
        self::assertEquals(0, count($config->get()["webhooks"]), "The webhook supposed to be deleted.");
    }
}
