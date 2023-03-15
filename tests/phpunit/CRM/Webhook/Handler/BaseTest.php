<?php

use Civi\WebhookAdmin\HeadlessTestCase;

/**
 * @group headless
 */
class CRM_Webhook_Handler_BaseTest extends HeadlessTestCase
{
    /**
     * Handle test case.
     * It shouldn't throw exception.
     */
    public function testHandle()
    {
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
