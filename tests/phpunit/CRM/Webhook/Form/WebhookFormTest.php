<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * WebhookForm tests
 *
 * @group headless
 */
class CRM_Webhook_Form_WebhookFormTest extends CRM_Webhook_Form_TestBase {

    /**
     * Build quick form test case.
     * Setup test configuration, preProcess then call the function.
     * It shouldn't throw exception.
     * The title should be set.
     */
    public function testBuildQuickForm() {
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
        $_POST["name"] = self::TEST_SETTINGS["webhooks"][0]["name"];
        $_POST["label"] = self::TEST_SETTINGS["webhooks"][0]["label"];
        $_POST["description"] = self::TEST_SETTINGS["webhooks"][0]["description"];
        $_POST["handler"] = self::TEST_SETTINGS["webhooks"][0]["handler"];
        $_POST["selector"] = self::TEST_SETTINGS["webhooks"][0]["selector"]."_something_else";
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        $config = new CRM_Webhook_Config(E::LONG_NAME);
        $config->remove();
        self::assertEmpty($form->postProcess());
    }
    public function testPostProcessDuplicatedInput() {
        $_POST["name"] = self::TEST_SETTINGS["webhooks"][0]["name"];
        $_POST["label"] = self::TEST_SETTINGS["webhooks"][0]["label"];
        $_POST["description"] = self::TEST_SETTINGS["webhooks"][0]["description"];
        $_POST["handler"] = self::TEST_SETTINGS["webhooks"][0]["handler"];
        $_POST["selector"] = self::TEST_SETTINGS["webhooks"][0]["selector"];
        $this->setupTestConfig();
        $form = new CRM_Webhook_Form_WebhookForm();
        self::assertEmpty($form->preProcess(), "PreProcess supposed to be empty.");
        self::assertEmpty($form->postProcess());
    }
    public function testPostProcessValidInput() {
        $_POST["name"] = self::TEST_SETTINGS["webhooks"][0]["name"];
        $_POST["label"] = self::TEST_SETTINGS["webhooks"][0]["label"];
        $_POST["description"] = self::TEST_SETTINGS["webhooks"][0]["description"];
        $_POST["handler"] = self::TEST_SETTINGS["webhooks"][0]["handler"];
        $_POST["selector"] = self::TEST_SETTINGS["webhooks"][0]["selector"]."_something_else";
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
}
