<?php

use Civi\Webhook\Config;
use Civi\Webhook\HeadlessTestCase;
use CRM_Webhook_ExtensionUtil as E;

/**
 * @group headless
 */
class CRM_Webhook_Form_WebhookBaseTest extends HeadlessTestCase
{
    /**
     * @return void
     */
    public function testPreProcessExistingConfig()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookBase();
        try {
            self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception with valid db.");
        }
    }

    /**
     * @return void
     */
    public function testPreProcessMissingConfig()
    {
        $form = new CRM_Webhook_Form_WebhookBase();
        $config = new Config(E::LONG_NAME);
        $config->remove();
        try {
            self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception with valid db.");
        }
    }
}
