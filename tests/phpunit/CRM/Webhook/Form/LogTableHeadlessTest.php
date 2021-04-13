<?php

use CRM_Webhook_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Tests for the logTable backend logic.
 *
 * @group headless
 */
class CRM_Webhook_Form_LogTableHeadlessTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface, TransactionalInterface
{
    const TEST_SETTINGS = [
        "sequence" => 1,
        "webhooks" => [
            0 => [
                "id" => 0,
                "name" => "test name",
                "description" => "test description",
                "handler" => "test_handler",
                "selector" => "test-selector",
                "processor" => "test-processor",
            ],
        ],
        "logs" => [],
    ];

    public function setUpHeadless()
    {
        return \Civi\Test::headless()
            ->installMe(__DIR__)
            ->apply();
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
    protected function setupTestConfig()
    {
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->create();
        self::assertTrue($config->update(self::TEST_SETTINGS), "Config update has to be successful.");
    }
    /**
     * PreProcess test case with existing config.
     * Setup test configuration then call the function.
     * It shouldn't throw exception.
     */
    public function testPreProcessExistingConfig()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_LogTable();
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
        $form = new CRM_Webhook_Form_LogTable();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->remove();
        self::expectException(CRM_Core_Exception::class);
        self::expectExceptionMessage(E::SHORT_NAME."_config config invalid.");
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
    }

    /**
     * Build quick form test case.
     * Setup test configuration, preProcess then call the function.
     * It shouldn't throw exception.
     * The title should be set.
     */
    public function testBuildQuickFormWithoutLogs()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_LogTable();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        self::assertEquals("Webhook Logs", $form->getTitle(), "Invalid form title.");
    }
    public function testBuildQuickFormWithLogs()
    {
        $this->setupTestConfig();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->insertLog([ "raw" => "", "get" => [], "post" => [], "header" => []]);
        $form = new CRM_Webhook_Form_LogTable();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
        self::assertEquals("Webhook Logs", $form->getTitle(), "Invalid form title.");
    }

    /**
     * Post Process test case. Config should be empty.
     */
    public function testPostProcess()
    {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_LogTable();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->insertLog([ "raw" => "", "get" => [], "post" => [], "header" => []]);
        $config->load();
        self::assertEquals(1, count($config->get()["logs"]), "Invalid number of log entries.");
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
        $config->load();
        self::assertEquals(0, count($config->get()["logs"]), "Invalid number of log entries.");
    }
}
