<?php

/**
 * Webhook Handler Base
 */
abstract class CRM_Webhook_Handler_Base {
    /**
     * Webhook Processor
     *
     * @var \CRM_Webhook_Processor_Base
     */
    protected CRM_Webhook_Processor_Base $processor;

    /**
     * Handler options
     *
     * @var array
     */
    protected array $options;

    /**
     * Request data
     *
     * @var array|null
     */
    protected ?array $data;

    /**
     * CRM_Webhook_Handler_Base constructor.
     *
     * @param \CRM_Webhook_Processor_Base $processor Webhook Processor
     */
    public function __construct(CRM_Webhook_Processor_Base $processor, array $options = []) {
        $this->processor = $processor;
        $this->options = $options;
    }

    /**
     * Process input
     */
    protected function processInput() {
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
    public function handle() {
        $this->processInput();
        $this->validate();
        $this->authenticate();
    }
}
