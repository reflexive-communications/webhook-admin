<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * WebhookBase tests
 *
 * @group headless
 */
class CRM_Webhook_Form_WebhookBaseTest extends CRM_Webhook_Form_TestBase
{

    /**
     * PreProcess test case with existing config.
     * Setup test configuration then call the function.
     * It shouldn't throw exception.
     */
    public function testPreProcessExistingConfig()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookBase();
        try {
            self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception with valid db.");
        }
    }

    /**
     * PreProcess test case with deleted config.
     * Setup test configuration then call the function.
     * It should throw exception.
     */
    public function testPreProcessMissingConfig()
    {
        $form = new CRM_Webhook_Form_WebhookBase();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->remove();
        self::expectException(CRM_Core_Exception::class, "Invalid exception class.");
        self::expectExceptionMessage(E::SHORT_NAME."_configuration config invalid.", "Invalid exception message.");
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
    }
}
