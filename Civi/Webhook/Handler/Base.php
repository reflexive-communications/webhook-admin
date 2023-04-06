<?php

namespace Civi\Webhook\Handler;

/**
 * Webhook Handler Base
 */
abstract class Base
{
    /**
     * Webhook Processor
     *
     * @var \Civi\Webhook\Processor\Base
     */
    protected $processor;

    /**
     * Handler options
     *
     * @var array
     */
    protected $options;

    /**
     * Request data
     *
     * @var array|null
     */
    protected $data;

    /**
     * @param \Civi\Webhook\Processor\Base $processor Webhook Processor
     * @param array $options Webhook options
     */
    public function __construct(\Civi\Webhook\Processor\Base $processor, array $options = [])
    {
        $this->processor = $processor;
        $this->options = $options;
    }

    /**
     * Process input
     */
    protected function processInput(): void
    {
        $this->data = $this->processor->input();
    }

    /**
     * Authenticate request
     *
     * @return bool
     */
    abstract protected function authenticate(): bool;

    /**
     * Validate request data
     *
     * @return bool
     */
    abstract protected function validate(): bool;

    /**
     * Handle request
     */
    public function handle(): void
    {
        $this->processInput();
        $this->validate();
        $this->authenticate();
    }
}
