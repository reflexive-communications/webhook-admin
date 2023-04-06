<?php

namespace Civi\Webhook\Processor;

use Civi\Webhook\HeadlessTestCase;
use CRM_Core_Exception_PrematureExitException;

/**
 * @group headless
 */
class BaseTest extends HeadlessTestCase
{
    /**
     * @return void
     */
    public function testOutput()
    {
        $stub = $this->getMockForAbstractClass('Civi\Webhook\Processor\Base');
        $testData = ['key' => 'value'];
        self::expectException(CRM_Core_Exception_PrematureExitException::class);
        self::assertEmpty($stub->output($testData), 'Output supposed to be empty.');
    }
}
