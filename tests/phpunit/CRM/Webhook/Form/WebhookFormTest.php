<?php

use Civi\Api4\WebhookLegacy;
use Civi\Webhook\HeadlessTestCase;

/**
 * @group headless
 */
class CRM_Webhook_Form_WebhookFormTest extends HeadlessTestCase
{
    /**
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
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

    /**
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testBuildQuickFormWithId()
    {
        $hook = WebhookLegacy::create(false)
            ->addValue('query_string', 'valid_listener_build')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'Civi\Webhook\Handler\Logger')
            ->addValue('processor', 'Civi\Webhook\Processor\Dummy')
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
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testSetDefaultValuesNoId()
    {
        $this->setGlobals('id', null);
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        $defaults = $form->setDefaultValues();
        self::assertSame([], $defaults, 'Should be empty without id.');
    }

    /**
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testSetDefaultValuesNotExistingId()
    {
        $hook = WebhookLegacy::create(false)
            ->addValue('query_string', 'valid_listener_not_existing_id')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'Civi\Webhook\Handler\Logger')
            ->addValue('processor', 'Civi\Webhook\Processor\Dummy')
            ->execute()
            ->first();
        $this->setGlobals('id', $hook['id'].$hook['id']);
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        $defaults = $form->setDefaultValues();
        self::assertSame([], $defaults, 'Should be empty with not existing id.');
    }

    /**
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testSetDefaultValuesGoodId()
    {
        $hook = WebhookLegacy::create(false)
            ->addValue('query_string', 'valid_listener_valid_id')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'Civi\Webhook\Handler\Logger')
            ->addValue('processor', 'Civi\Webhook\Processor\Dummy')
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
     * @return void
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
     * @return void
     * @throws \API_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testConfigValidator()
    {
        $hook = WebhookLegacy::create(false)
            ->addValue('query_string', 'valid_listener_config')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'Civi\Webhook\Handler\Logger')
            ->addValue('processor', 'Civi\Webhook\Processor\Dummy')
            ->execute()
            ->first();

        $testData = [
            [
                'data' => ['query_string' => 'new-selector'],
                'expectedResult' => true,
            ],
            [
                'data' => ['query_string' => $hook['query_string']],
                'expectedResult' => false,
            ],
            [
                'data' => [
                    'query_string' => $hook['query_string'],
                    'id' => $hook['id'],
                ],
                'expectedResult' => true,
            ],
        ];
        foreach ($testData as $t) {
            $form = new CRM_Webhook_Form_WebhookForm();
            $form->_flagSubmitted = true;
            $form->addRules();
            $form->_submitValues = $t['data'];
            self::assertSame($t['expectedResult'], $form->validate(), 'Invalid validation result.');
        }
    }

    /**
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testPostProcessDuplicatedInput()
    {
        $hook = WebhookLegacy::create(false)
            ->addValue('query_string', 'valid_listener_duplicated')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'Civi\Webhook\Handler\Logger')
            ->addValue('processor', 'Civi\Webhook\Processor\Dummy')
            ->execute()
            ->first();
        $this->setGlobals('id', null);
        $_POST['name'] = 'validName';
        $_POST['description'] = 'valid-description';
        $_POST['handler'] = 'Civi\Webhook\Handler\Logger';
        $_POST['query_string'] = 'valid_listener_duplicated';
        $_POST['processor'] = 'Civi\Webhook\Processor\Dummy';
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        self::expectException(PEAR_Exception::class);
        self::assertEmpty($form->postProcess());
    }

    /**
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testPostProcessValidInput()
    {
        $currentNumber = WebhookLegacy::get(false)
            ->selectRowCount()
            ->execute();
        $this->setGlobals('id', null);
        $_POST['name'] = 'validName';
        $_POST['description'] = 'valid-description';
        $_POST['handler'] = 'Civi\Webhook\Handler\Logger';
        $_POST['query_string'] = 'valid_listener_input';
        $_POST['processor'] = 'Civi\Webhook\Processor\Dummy';
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
        $newNumber = WebhookLegacy::get(false)
            ->selectRowCount()
            ->execute();
        self::assertSame(count($currentNumber) + 1, count($newNumber));
    }

    /**
     * @return void
     * @throws \API_Exception
     * @throws \CRM_Core_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testPostProcessValidInputEdition()
    {
        $hook = WebhookLegacy::create(false)
            ->addValue('query_string', 'valid_listener_edit')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'Civi\Webhook\Handler\Logger')
            ->addValue('processor', 'Civi\Webhook\Processor\Dummy')
            ->execute()
            ->first();
        $currentNumber = WebhookLegacy::get(false)
            ->selectRowCount()
            ->execute();
        $this->setGlobals('id', $hook['id']);
        $_POST['name'] = 'validName';
        $_POST['description'] = 'valid-description';
        $_POST['handler'] = 'Civi\Webhook\Handler\Logger';
        $_POST['query_string'] = 'valid_listener_edited';
        $_POST['processor'] = 'Civi\Webhook\Processor\Dummy';
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), 'PreProcess supposed to be empty.');
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
        $newNumber = WebhookLegacy::get(false)
            ->selectRowCount()
            ->execute();
        self::assertSame(count($currentNumber), count($newNumber));
    }
}
