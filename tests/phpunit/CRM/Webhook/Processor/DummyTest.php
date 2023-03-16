<?php

use Civi\WebhookAdmin\HeadlessTestCase;

/**
 * @group headless
 */
class CRM_Webhook_Processor_DummyTest extends HeadlessTestCase
{
    /**
     * Input test case.
     */
    public function testInput()
    {
        $_POST = [];
        $_GET = [];
        $_SERVER['HTTP_REFERER'] = 'v1';
        $_SERVER['HTTP_USER_AGENT'] = 'v2';
        $dummy = new CRM_Webhook_Processor_Dummy();
        $input = $dummy->input();
        self::assertEquals(['raw' => '', 'get' => [], 'post' => [], 'header' => ['REFERER' => 'v1', 'USER_AGENT' => 'v2']], $input, 'Input supposed to be empty in this case.');
    }
}
