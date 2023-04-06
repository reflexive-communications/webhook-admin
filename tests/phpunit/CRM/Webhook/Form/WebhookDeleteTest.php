<?php

use Civi\Api4\Webhook;
use Civi\Webhook\Config;
use Civi\Webhook\HeadlessTestCase;
use CRM_Webhook_ExtensionUtil as E;

/**
 * @group headless
 */
class CRM_Webhook_Form_WebhookDeleteTest extends HeadlessTestCase
{
    /**
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testBuildQuickFormWithId()
    {
        $hook = Webhook::create(false)
            ->addValue('query_string', 'valid_listener')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'Civi\Webhook\Handler\Logger')
            ->addValue('processor', 'Civi\Webhook\Processor\Dummy')
            ->execute()
            ->first();
        $this->setGlobals('id', $hook['id']);
        $form = new CRM_Webhook_Form_WebhookDelete();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        self::assertEquals('Webhook Delete', $form->getTitle(), 'Invalid form title.');
    }

    /**
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testPostProcessValidId()
    {
        $hook = Webhook::create(false)
            ->addValue('query_string', 'valid_listener_post_process')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'Civi\Webhook\Handler\Logger')
            ->addValue('processor', 'Civi\Webhook\Processor\Dummy')
            ->execute()
            ->first();
        $this->setGlobals('id', $hook['id']);
        $form = new CRM_Webhook_Form_WebhookDelete();
        $config = new Config(E::LONG_NAME);
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        $deletedHook = Webhook::get(false)
            ->addWhere('id', '=', $hook['id'])
            ->execute();
        self::assertEquals(0, count($deletedHook), 'The webhook supposed to be deleted.');
    }
}
