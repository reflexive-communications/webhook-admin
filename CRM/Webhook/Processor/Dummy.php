<?php

/**
 * Webhook Dummy input Processor
 */
class CRM_Webhook_Processor_Dummy extends CRM_Webhook_Processor_Base
{
    /**
     * Process input
     *
     * @return array
     */
    public function input() {
        // Get contents from raw POST data
        $input = file_get_contents('php://input');

        return $input === false ? null : [ "data" => $input ];
    }
}
