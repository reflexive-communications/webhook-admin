<?php

/**
 * Webhook XML Processor
 */
class CRM_Webhook_Processor_XML extends CRM_Webhook_Processor_Base
{
    /**
     * Process input
     *
     * @return array|null
     */
    public function input()
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
