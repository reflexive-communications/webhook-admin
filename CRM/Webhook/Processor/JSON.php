<?php

class CRM_Webhook_Processor_JSON extends CRM_Webhook_Processor_Base
{
  protected function decode($input)
  {
    $decoded = json_decode($input);
    if (is_null($decoded) && trim($input) != '') {
      throw new CRM_Core_Exception(ts('Unable to decode JSON'));
    }

    return $decoded;
  }
  public function input()
  {
    $input = file_get_contents('php://input');

    if (!CRM_Utils_JSON::isValidJSON($input)) {
      // return false;
    }

    return $this->decode($input);
  }

  public function output($result)
  {
    CRM_Utils_JSON::output($result);
  }

}
