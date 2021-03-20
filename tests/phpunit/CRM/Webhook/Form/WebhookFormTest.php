<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * WebhookForm tests
 *
 * @group headless
 */
class CRM_Webhook_Form_WebhookFormTest extends CRM_Webhook_Form_TestBase {

    /**
     * Build quick form test cases.
     * Setup test configuration, preProcess then call the function.
     * It shouldn't throw exception.
     * The title should be set.
     */
    public function testBuildQuickFormWithoutId() {
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
        self::assertEquals("Webhook Form", $form->getTitle(), "Invalid form title.");
    }
    public function testBuildQuickFormWithId() {
        $this->setGlobals("id", self::TEST_SETTINGS["webhooks"][0]["id"]);
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        try {
            self::assertEmpty($form->buildQuickForm());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
        self::assertEquals("Webhook Form", $form->getTitle(), "Invalid form title.");
    }

    /**
     * Default values test cases.
     * Without id - no defaults.
     * With not existing id - no defaults.
     * existing id - defaults.
     */
    public function testSetDefaultValuesNoId() {
        $this->setGlobals("id", null);
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        $defaults = $form->setDefaultValues();
        self::assertEquals([], $defaults, "Should be empty without id.");
    }
    public function testSetDefaultValuesNotExistingId() {
        $this->setGlobals("id", self::TEST_SETTINGS["sequence"]);
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        $defaults = $form->setDefaultValues();
        self::assertEquals([], $defaults, "Should be empty with not existing id.");
    }
    public function testSetDefaultValuesGoodId() {
        $this->setGlobals("id", self::TEST_SETTINGS["webhooks"][0]["id"]);
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        $defaults = $form->setDefaultValues();
        self::assertEquals(self::TEST_SETTINGS["webhooks"][self::TEST_SETTINGS["webhooks"][0]["id"]], $defaults, "Should be the same.");
    }

    /**
     * Add Rules test case.
     * It shouldn't throw exception.
     */
    public function testAddRules() {
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
    public function testConfigValidator() {
        $testData = [
            [
                "data" => [
                    "selector" => "new-selector",
                ],
                "expectedResult" => true,
            ],
            [
                "data" => [
                    "selector" => self::TEST_SETTINGS["webhooks"][0]["selector"],
                ],
                "expectedResult" => ["selector" => "The selector '".self::TEST_SETTINGS["webhooks"][0]["selector"]."' already set for the '".self::TEST_SETTINGS["webhooks"][0]["name"]."' webhook."],
            ],
        ];
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        foreach ($testData as $t) {
            self::assertEquals($t["expectedResult"], $form->validateSelector($t["data"], null, ["config" => $config]), "Should return the expected value.");
        }
    }

    /**
     * Post Process test cases.
     */
    public function testPostProcessDeletedConfig() {
        $this->setGlobals("id", null);
        $_POST["name"] = self::TEST_SETTINGS["webhooks"][0]["name"];
        $_POST["description"] = self::TEST_SETTINGS["webhooks"][0]["description"];
        $_POST["handler"] = self::TEST_SETTINGS["webhooks"][0]["handler"];
        $_POST["selector"] = self::TEST_SETTINGS["webhooks"][0]["selector"]."_something_else";
        $_POST["processor"] = self::TEST_SETTINGS["webhooks"][0]["processor"];
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->remove();
        self::assertEmpty($form->postProcess());
    }
    public function testPostProcessDuplicatedInput() {
        $this->setGlobals("id", null);
        $_POST["name"] = self::TEST_SETTINGS["webhooks"][0]["name"];
        $_POST["description"] = self::TEST_SETTINGS["webhooks"][0]["description"];
        $_POST["handler"] = self::TEST_SETTINGS["webhooks"][0]["handler"];
        $_POST["selector"] = self::TEST_SETTINGS["webhooks"][0]["selector"];
        $_POST["processor"] = self::TEST_SETTINGS["webhooks"][0]["processor"];
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        self::assertEmpty($form->postProcess());
    }
    public function testPostProcessValidInput() {
        $this->setGlobals("id", null);
        $_POST["name"] = self::TEST_SETTINGS["webhooks"][0]["name"];
        $_POST["description"] = self::TEST_SETTINGS["webhooks"][0]["description"];
        $_POST["handler"] = self::TEST_SETTINGS["webhooks"][0]["handler"];
        $_POST["selector"] = self::TEST_SETTINGS["webhooks"][0]["selector"]."_something_else";
        $_POST["processor"] = self::TEST_SETTINGS["webhooks"][0]["processor"];
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->load();
        self::assertEquals(2, count($config->get()["webhooks"]));
    }
    public function testPostProcessValidInputEdition() {
        $this->setGlobals("id", self::TEST_SETTINGS["webhooks"][0]["id"]);
        $_POST["name"] = self::TEST_SETTINGS["webhooks"][0]["name"];
        $_POST["description"] = self::TEST_SETTINGS["webhooks"][0]["description"];
        $_POST["handler"] = self::TEST_SETTINGS["webhooks"][0]["handler"];
        $_POST["selector"] = self::TEST_SETTINGS["webhooks"][0]["selector"]."_something_else";
        $_POST["processor"] = self::TEST_SETTINGS["webhooks"][0]["processor"];
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        $config->load();
        self::assertEquals(1, count($config->get()["webhooks"]), "We have only 1 hook");
        try {
            self::assertEmpty($form->postProcess());
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception.");
        }
        $config->load();
        self::assertEquals(1, count($config->get()["webhooks"]), "Number of hooks should be the same.");
    }
}
