<?php

namespace Civi\Webhook\Processor;

use Exception;
use SimpleXMLElement;

/**
 * Webhook XML Processor
 */
class XML extends Base
{
    /**
     * Process input
     *
     * @return array|null
     */
    public function input(): ?array
    {
        try {
            // Get contents from raw POST data
            $input = file_get_contents('php://input');

            // Disable external entity parsing to prevent XEE attack
            libxml_disable_entity_loader(true);

            // Load XML
            $xml = new SimpleXMLElement($input);

            // Encode & decode to JSON to convert XML_Element to array
            $json = json_encode($xml);

            return [
                $xml->getName() => json_decode($json, true),
            ];
        } catch (Exception $e) {
            $this->error(ts('Unable to parse XML.'), true);
        }
    }
}
