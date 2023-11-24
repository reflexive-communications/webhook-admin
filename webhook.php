<?php

require_once 'webhook.civix.php';

use CRM_Webhook_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function webhook_civicrm_config(&$config): void
{
    _webhook_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function webhook_civicrm_navigationMenu(&$menu): void
{
    _webhook_civix_insert_navigation_menu($menu, 'Administer', [
        'label' => E::ts('Webhooks (Legacy)'),
        'name' => 'webhooks',
        'url' => 'civicrm/admin/webhooks/settings',
        'permission' => 'administer CiviCRM',
        'separator' => 2,
        'active' => 1,
    ]);
    _webhook_civix_navigationMenu($menu);
}
