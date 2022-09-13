<?php

/**
 * This is a generic test class for the extension (implemented with PHPUnit).
 */
class CRM_Webhook_Processor_DummyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * The setup() method is executed before the test is executed (optional).
     */
    public function setUp(): void
    {
        parent::setUp();
        $_SERVER['HTTP_REFERER'] = 'v1';
        $_SERVER['HTTP_USER_AGENT'] = 'v2';
    }

    /**
     * The tearDown() method is executed after the test was executed (optional)
     * This can be used for cleanup.
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Input test case.
     */
    public function testInput()
    {
        $_POST = [];
        $_GET = [];
        $dummy = new CRM_Webhook_Processor_Dummy();
        $input = $dummy->input();
        self::assertEquals([ "raw" => "", "get" => [], "post" => [], "header" => ['REFERER' => 'v1', 'USER_AGENT' => 'v2']], $input, "Input supposed to be empty in this case.");
    }
}
