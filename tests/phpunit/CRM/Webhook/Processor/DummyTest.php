<?php

/**
 * This is a generic test class for the extension (implemented with PHPUnit).
 */
class CRM_Webhook_Processor_DummyTest extends \PHPUnit\Framework\TestCase {

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
     * Input test case.
     */
    public function testInput() {
        $dummy = new CRM_Webhook_Processor_Dummy();
        try {
            $input = $dummy->input();
            self::assertEquals(["data"=>"",], $input, "Input supposed to be empty in this case.");
        } catch (Exception $e) {
            self::fail("It shouldn't throw exception. ".$e->getMessage());
        }
    }

}
