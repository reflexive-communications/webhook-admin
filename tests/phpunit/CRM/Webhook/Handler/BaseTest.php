<?php

/**
 * This is a generic test class for the extension (implemented with PHPUnit).
 */
class CRM_Webhook_Handler_BaseTest extends \PHPUnit\Framework\TestCase {

    /**
     * The setup() method is executed before the test is executed (optional).
     */
    public function setUp(): void {
        parent::setUp();
    }

    /**
     * The tearDown() method is executed after the test was executed (optional)
     * This can be used for cleanup.
     */
    public function tearDown(): void {
        parent::tearDown();
    }

    /**
     * Handle test case.
     *
     * It shouldn't throw exception.
     */
    public function testHandle() {
        $processor = new CRM_Webhook_Processor_Dummy();
        $stub = $this->getMockForAbstractClass("CRM_Webhook_Handler_Base", [$processor]);
        $stub->method("authenticate")->willReturn(true);
        $stub->method("validate")->willReturn(true);
        try {
            self::assertEmpty($stub->handle(), "It should be empty.");
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception. ".$e->getMessage());
        }
    }
}
