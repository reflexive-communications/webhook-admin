<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Webhook page controller
 */
class CRM_Webhook_Page_Webhooks extends CRM_Core_Page
{

  /**
   * Get installed webhooks
   *
   * @return array
   */
  protected function getInstalledWebhooks()
  {
    $webhook = [];
    $endpoints = E::path('xml/Menu/endpoints.xml');

    // Check if file readable
    if (!is_readable($endpoints)) {
      return $webhook;
    }

    // Load from XML
    $xml = new SimpleXMLElement(file_get_contents($endpoints));

    // Loop through items
    foreach ($xml->children() as $item) {
      // Webhook name
      $name = (string)$item->name;

      // Simple elements
      $webhook[$name]['name'] = $name;
      $webhook[$name]['path'] = (string)$item->path;
      $webhook[$name]['comment'] = (string)$item->comment;
      $webhook[$name]['desc'] = (string)$item->desc;

      // Default, if processor supplied it will be overwritten below
      $webhook[$name]['processor'] = 'auto detect';

      // Parse page_arguments element
      $page_args = explode(',', (string)$item->page_arguments);

      // Loop through page arguments
      foreach ($page_args as $arg) {
        $arg = explode('=', $arg);
        switch ($arg[0]) {
          case 'processor':
            $webhook[$name]['processor'] = $arg[1];
            break;
          case 'handler':
            $webhook[$name]['handler'] = $arg[1];
            break;
        }
      }
    }

    return $webhook;
  }

  /**
   * Builds page
   *
   * @return void|null
   */
  public function run()
  {
    // Get installed webhooks
    $webhooks = $this->getInstalledWebhooks();
    $this->assign('webhooks', $webhooks);

    parent::run();
  }
}
