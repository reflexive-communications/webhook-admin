<?php

use Civi\WebhookAdmin\HeadlessTestCase;
use CRM_Webhook_ExtensionUtil as E;

/**
 * @group headless
 */
class CRM_Webhook_Form_LogTableTest extends HeadlessTestCase
{
    /**
     * @return void
     */
    public function testPreProcessExistingConfig()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_LogTable();
        try {
            self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception with valid db.");
        }
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testPreProcessMissingConfig()
    {
        $form = new CRM_Webhook_Form_LogTable();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->remove();
        self::expectException(CRM_Core_Exception::class);
        self::expectExceptionMessage(E::LONG_NAME.'_config config invalid.');
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testBuildQuickFormWithoutLogs()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_LogTable();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        self::assertEquals('Webhook Logs', $form->getTitle(), 'Invalid form title.');
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testBuildQuickFormWithLogs()
    {
        $this->setupTestConfig();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->insertLog(['raw' => '', 'get' => [], 'post' => [], 'header' => []]);
        $form = new CRM_Webhook_Form_LogTable();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        self::assertEquals('Webhook Logs', $form->getTitle(), 'Invalid form title.');
    }

    /**
     * @return void
     * @throws \CRM_Core_Exception
     */
    public function testPostProcess()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_LogTable();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->insertLog(['raw' => '', 'get' => [], 'post' => [], 'header' => []]);
        $config->load();
        self::assertEquals(1, count($config->get()['logs']), 'Invalid number of log entries.');
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
        $config->load();
        self::assertEquals(0, count($config->get()['logs']), 'Invalid number of log entries.');
    }
}
