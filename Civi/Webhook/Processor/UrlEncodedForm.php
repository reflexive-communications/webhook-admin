<?php

namespace Civi\Webhook\Processor;

/**
 * Webhook URL encoded form Processor
 */
class UrlEncodedForm extends Base
{
    /**
     * Process input
     *
     * @return array
     */
    public function input(): array
    {
        $data = [];

        // Loop through $_POST data & trim
        foreach ($_POST as $key => $value) {
            $data[trim($key)] = trim($value);
        }

        return $data;
    }
}
