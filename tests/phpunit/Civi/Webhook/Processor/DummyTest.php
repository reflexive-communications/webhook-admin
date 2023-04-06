<?php

namespace Civi\Webhook\Processor;

use Civi\Webhook\HeadlessTestCase;

/**
 * @group headless
 */
class DummyTest extends HeadlessTestCase
{
    /**
     * @return void
     */
    public function testInput()
    {
        $_POST = [];
        $_GET = [];
        $_SERVER['HTTP_REFERER'] = 'v1';
        $_SERVER['HTTP_USER_AGENT'] = 'v2';
        $dummy = new Dummy();
        $input = $dummy->input();
        self::assertEquals(['raw' => '', 'get' => [], 'post' => [], 'header' => ['REFERER' => 'v1', 'USER_AGENT' => 'v2']], $input, 'Input supposed to be empty in this case.');
    }
}
