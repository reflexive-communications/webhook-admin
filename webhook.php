<?php

require_once 'webhook.civix.php';

use CRM_Webhook_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function webhook_civicrm_config(&$config)
{
    _webhook_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function webhook_civicrm_xmlMenu(&$files)
{
    _webhook_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function webhook_civicrm_install()
{
    _webhook_civix_civicrm_install();

    $installer = _webhook_civix_upgrader();
    $installer->install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function webhook_civicrm_postInstall()
{
    _webhook_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function webhook_civicrm_uninstall()
{
    _webhook_civix_civicrm_uninstall();

    $installer = _webhook_civix_upgrader();
    $installer->uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function webhook_civicrm_enable()
{
    _webhook_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function webhook_civicrm_disable()
{
    _webhook_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function webhook_civicrm_upgrade($op, CRM_Queue_Queue $queue = null)
{
    return _webhook_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function webhook_civicrm_managed(&$entities)
{
    _webhook_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 * Generate a list of case-types.
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function webhook_civicrm_caseTypes(&$caseTypes)
{
    _webhook_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 * Generate a list of Angular modules.
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function webhook_civicrm_angularModules(&$angularModules)
{
    _webhook_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function webhook_civicrm_alterSettingsFolders(&$metaDataFolders = null)
{
    _webhook_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function webhook_civicrm_entityTypes(&$entityTypes)
{
    _webhook_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function webhook_civicrm_themes(&$themes)
{
    _webhook_civix_civicrm_themes($themes);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function webhook_civicrm_navigationMenu(&$menu)
{
    _webhook_civix_insert_navigation_menu($menu, 'Administer', [
        'label' => E::ts('Webhooks'),
        'name' => 'webhooks',
        'url' => 'civicrm/admin/webhooks/settings',
        'permission' => 'administer CiviCRM',
        'separator' => 2,
        'active' => 1,
    ]);
    _webhook_civix_navigationMenu($menu);
}
