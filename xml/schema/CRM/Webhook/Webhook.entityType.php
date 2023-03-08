<?php
// This file declares a new entity type. For more details, see "hook_civicrm_entityTypes" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
return [
    [
        'name' => 'Webhook',
        'class' => 'CRM_Webhook_DAO_Webhook',
        'table' => 'civicrm_webhook',
    ],
];
