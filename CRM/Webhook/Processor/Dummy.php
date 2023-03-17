<?php

/**
 * Webhook Dummy input Processor
 */
class CRM_Webhook_Processor_Dummy extends CRM_Webhook_Processor_Base
{
    /**
     * @return array
     */
    private function getHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = substr($key, 5);
            $headers[$header] = $value;
        }

        return $headers;
    }

    /**
     * Process input
     *
     * @return array
     */
    public function input(): array
    {
        // Get contents from raw POST data
        $inputRaw = file_get_contents('php://input');
        $get = $_GET;
        $post = $_POST;
        $headers = $this->getHeaders();

        return ['raw' => $inputRaw, 'get' => $get, 'post' => $post, 'header' => $headers];
    }
}
