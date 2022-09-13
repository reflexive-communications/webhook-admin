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
        $hook = \Civi\Api4\Webhook::create(false)
            ->addValue('query_string', 'valid_listener')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'CRM_Webhook_Handler_Logger')
            ->addValue('processor', 'CRM_Webhook_Processor_Dummy')
            ->execute()
            ->first();
        $this->setGlobals("id", $hook['id']);
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
        $hook = \Civi\Api4\Webhook::create(false)
            ->addValue('query_string', 'valid_listener')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'CRM_Webhook_Handler_Logger')
            ->addValue('processor', 'CRM_Webhook_Processor_Dummy')
            ->execute()
            ->first();
        $this->setGlobals("id", $hook['id']);
        $form = new CRM_Webhook_Form_WebhookDelete();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        $deletedHook = \Civi\Api4\Webhook::get(false)
            ->addWhere('id', '=', $hook['id'])
            ->execute();
        self::assertEquals(0, count($deletedHook), "The webhook supposed to be deleted.");
    }
}
