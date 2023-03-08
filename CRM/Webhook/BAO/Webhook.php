<?php

use CRM_Webhook_ExtensionUtil as E;

class CRM_Webhook_BAO_Webhook extends CRM_Webhook_DAO_Webhook
{
    /**
     * Create a new Webhook based on array-data
     *
     * @param array $params key-value pairs
     *
     * @return CRM_Webhook_DAO_Webhook|NULL
     * public static function create($params) {
     * $className = 'CRM_Webhook_DAO_Webhook';
     * $entityName = 'Webhook';
     * $hook = empty($params['id']) ? 'create' : 'edit';
     * CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
     * $instance = new $className();
     * $instance->copyValues($params);
     * $instance->save();
     * CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);
     * return $instance;
     * } */
}
