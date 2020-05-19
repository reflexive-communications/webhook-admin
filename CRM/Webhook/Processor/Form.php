<?php

/**
 * Webhook URL encoded form Processor
 */
class CRM_Webhook_Processor_Form extends CRM_Webhook_Processor_Base
{
  /**
   * Process input
   *
   * @return array
   */
  public function input()
  {
    $data = [];

    // Loop through $_POST data & trim each value
    foreach ($_POST as $key => $value) {
      $data->$key = trim($value);
    }

    return $data;
  }
}
