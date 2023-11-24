<?php

namespace Civi\Webhook;

use Civi\Api4\WebhookLegacy;
use Exception;

/**
 * @group headless
 */
class DispatcherTest extends HeadlessTestCase
{
    /**
     * @return void
     * @throws \API_Exception
     */
    public function testRunMissingListener()
    {
        if (isset($_GET['listener'])) {
            unset($_GET['listener']);
        }
        $d = new Dispatcher();
        self::expectException(Exception::class);
        self::expectExceptionMessage('Missing listener.');
        self::assertEmpty($d->run(), 'Run supposed to be empty.');
    }

    /**
     * @return void
     * @throws \API_Exception
     */
    public function testRunInvalidListener()
    {
        $_GET['listener'] = 'not-existing-listener';
        $d = new Dispatcher();
        self::expectException(Exception::class);
        self::expectExceptionMessage('Invalid listener.');
        self::assertEmpty($d->run(), 'Run supposed to be empty.');
    }

    /**
     * @return void
     * @throws \API_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testRunValidListener()
    {
        WebhookLegacy::create(false)
            ->addValue('query_string', 'valid_listener')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'Civi\Webhook\Handler\Logger')
            ->addValue('processor', 'Civi\Webhook\Processor\Dummy')
            ->execute();
        $_GET['listener'] = 'valid_listener';
        $d = new Dispatcher();
        try {
            self::assertEmpty($d->run(), 'Run supposed to be empty.');
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception.");
        }
    }

    /**
     * @return void
     * @throws \API_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function testRunValidListenerWithOptions()
    {
        WebhookLegacy::create(false)
            ->addValue('query_string', 'valid_listener_with_options')
            ->addValue('name', 'validName')
            ->addValue('description', 'valid-description')
            ->addValue('handler', 'Civi\Webhook\Handler\Logger')
            ->addValue('processor', 'Civi\Webhook\Processor\Dummy')
            ->addValue('options', ['k' => 'v'])
            ->execute();
        $_GET['listener'] = 'valid_listener_with_options';
        $d = new Dispatcher();
        try {
            self::assertEmpty($d->run(), 'Run supposed to be empty.');
        } catch (Exception $e) {
            self::fail("Shouldn't throw exception.");
        }
    }
}
