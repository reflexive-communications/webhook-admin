<?php

use Civi\Api4\Webhook;
use Civi\WebhookAdmin\HeadlessTestCase;

/**
 * @group headless
 */
class CRM_Webhook_Form_WebhookFormTest extends HeadlessTestCase
{
    /**
     * Build quick form test cases.
     * Setup test configuration, preProcess then call the function.
     * It shouldn't throw exception.
     * The title should be set.
     */
    public function testBuildQuickFormWithoutId()
    {
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        self::assertSame('Webhook Form', $form->getTitle(), 'Invalid form title.');
    }

    public function testBuildQuickFormWithId()
    {
        $hook = Webhook::create(false)
            ->addValue('query_string', 'valid_listener_build')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'CRM_Webhook_Handler_Logger')
            ->addValue('processor', 'CRM_Webhook_Processor_Dummy')
            ->execute()
            ->first();
        $this->setGlobals('id', $hook['id']);
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
        self::assertSame('Webhook Form', $form->getTitle(), 'Invalid form title.');
    }

    /**
     * Default values test cases.
     * Without id - no defaults.
     * With not existing id - no defaults.
     * existing id - defaults.
     */
    public function testSetDefaultValuesNoId()
    {
        $this->setGlobals('id', null);
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        $defaults = $form->setDefaultValues();
        self::assertSame([], $defaults, 'Should be empty without id.');
    }

    public function testSetDefaultValuesNotExistingId()
    {
        $hook = Webhook::create(false)
            ->addValue('query_string', 'valid_listener_not_existing_id')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'CRM_Webhook_Handler_Logger')
            ->addValue('processor', 'CRM_Webhook_Processor_Dummy')
            ->execute()
            ->first();
        $this->setGlobals('id', $hook['id'].$hook['id']);
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        $defaults = $form->setDefaultValues();
        self::assertSame([], $defaults, 'Should be empty with not existing id.');
    }

    public function testSetDefaultValuesGoodId()
    {
        $hook = Webhook::create(false)
            ->addValue('query_string', 'valid_listener_valid_id')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'CRM_Webhook_Handler_Logger')
            ->addValue('processor', 'CRM_Webhook_Processor_Dummy')
            ->execute()
            ->first();
        unset($hook['custom']);
        unset($hook['check_permissions']);
        $this->setGlobals('id', $hook['id']);
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        $defaults = $form->setDefaultValues();
        self::assertSame(count($hook), count($defaults));
        foreach ($hook as $k => $v) {
            self::assertSame($hook[$k], $defaults[$k], 'Should be the same.');
        }
    }

    /**
     * Add Rules test case.
     * It shouldn't throw exception.
     */
    public function testAddRules()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        try {
            self::assertEmpty($form->addRules());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
    }

    /**
     * Config Validator test cases.
     */
    public function testConfigValidator()
    {
        $hook = Webhook::create(false)
            ->addValue('query_string', 'valid_listener_config')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'CRM_Webhook_Handler_Logger')
            ->addValue('processor', 'CRM_Webhook_Processor_Dummy')
            ->execute()
            ->first();
        $testData = [
            [
                'data' => [
                    'query_string' => 'new-selector',
                ],
                'expectedResult' => true,
            ],
            [
                'data' => [
                    'query_string' => $hook['query_string'],
                ],
                'expectedResult' => ['query_string' => "The query string '".$hook['query_string']."' already set for the '".$hook['name']."' webhook."],
            ],
            [
                'data' => [
                    'query_string' => $hook['query_string'],
                    'id' => $hook['id'],
                ],
                'expectedResult' => true,
            ],
        ];
        $form = new CRM_Webhook_Form_WebhookForm();
        foreach ($testData as $t) {
            self::assertEquals($t['expectedResult'], $form->validateQueryString($t['data'], null, null), 'Should return the expected value.');
        }
    }

    public function testPostProcessDuplicatedInput()
    {
        $hook = Webhook::create(false)
            ->addValue('query_string', 'valid_listener_duplicated')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'CRM_Webhook_Handler_Logger')
            ->addValue('processor', 'CRM_Webhook_Processor_Dummy')
            ->execute()
            ->first();
        $this->setGlobals('id', null);
        $_POST['name'] = 'validName';
        $_POST['description'] = 'valid-description';
        $_POST['handler'] = 'CRM_Webhook_Handler_Logger';
        $_POST['query_string'] = 'valid_listener_duplicated';
        $_POST['processor'] = 'CRM_Webhook_Processor_Dummy';
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        self::expectException(PEAR_Exception::class);
        self::assertEmpty($form->postProcess());
    }

    public function testPostProcessValidInput()
    {
        $currentNumber = Webhook::get(false)
            ->selectRowCount()
            ->execute();
        $this->setGlobals('id', null);
        $_POST['name'] = 'validName';
        $_POST['description'] = 'valid-description';
        $_POST['handler'] = 'CRM_Webhook_Handler_Logger';
        $_POST['query_string'] = 'valid_listener_input';
        $_POST['processor'] = 'CRM_Webhook_Processor_Dummy';
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
        $newNumber = Webhook::get(false)
            ->selectRowCount()
            ->execute();
        self::assertSame(count($currentNumber) + 1, count($newNumber));
    }

    public function testPostProcessValidInputEdition()
    {
        $hook = Webhook::create(false)
            ->addValue('query_string', 'valid_listener_edit')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'CRM_Webhook_Handler_Logger')
            ->addValue('processor', 'CRM_Webhook_Processor_Dummy')
            ->execute()
            ->first();
        $currentNumber = Webhook::get(false)
            ->selectRowCount()
            ->execute();
        $this->setGlobals('id', $hook['id']);
        $_POST['name'] = 'validName';
        $_POST['description'] = 'valid-description';
        $_POST['handler'] = 'CRM_Webhook_Handler_Logger';
        $_POST['query_string'] = 'valid_listener_edited';
        $_POST['processor'] = 'CRM_Webhook_Processor_Dummy';
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
        $newNumber = Webhook::get(false)
            ->selectRowCount()
            ->execute();
        self::assertSame(count($currentNumber), count($newNumber));
    }
}
