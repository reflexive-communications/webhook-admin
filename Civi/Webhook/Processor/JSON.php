<?php

namespace Civi\Webhook\Processor;

/**
 * Webhook JSON input Processor
 */
class JSON extends Base
{
    /**
     * Process input
     *
     * @return mixed|null
     */
    public function input()
    {
        // Get contents from raw POST data
        $input = file_get_contents('php://input');

        // Decode JSON
        $decoded = json_decode($input, true);

        // Check if valid JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error(ts('Not valid JSON received.'), true);
        }

        return $decoded;
    }
}
