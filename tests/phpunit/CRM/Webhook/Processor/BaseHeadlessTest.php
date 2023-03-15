<?php

use Civi\WebhookAdmin\HeadlessTestCase;

/**
 * @group headless
 */
class CRM_Webhook_Processor_BaseHeadlessTest extends HeadlessTestCase
{
    /**
     * Output test case.
     */
    public function testOutput()
    {
        $stub = $this->getMockForAbstractClass('CRM_Webhook_Processor_Base');
        $testData = ["key" => "value"];
        self::expectException(CRM_Core_Exception_PrematureExitException::class);
        self::assertEmpty($stub->output($testData), "Output supposed to be empty.");
    }

    /**
     * Error test case.
     */
    public function testErrorWithOutput()
    {
        self::markTestIncomplete("This test has not been implemented yet.");
    }

    public function testErrorWithoutOutput()
    {
        self::markTestIncomplete("This test has not been implemented yet.");
    }
}
