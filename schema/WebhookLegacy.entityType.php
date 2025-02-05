<?php

use CRM_Webhook_ExtensionUtil as E;

return [
    'name' => 'WebhookLegacy',
    'table' => 'civicrm_webhook_legacy',
    'class' => 'CRM_Webhook_DAO_WebhookLegacy',
    'getInfo' => fn() => [
        'title' => E::ts('Webhook'),
        'title_plural' => E::ts('Webhooks'),
        'description' => E::ts('Webhook configs'),
        'log' => false,
    ],
    'getIndices' => fn() => [
        'index_query' => [
            'fields' => [
                'query_string' => true,
            ],
            'unique' => true,
        ],
    ],
    'getFields' => fn() => [
        'id' => [
            'title' => E::ts('Webhook ID'),
            'sql_type' => 'int unsigned',
            'input_type' => 'Number',
            'required' => true,
            'description' => E::ts('Unique Webhook ID'),
            'primary_key' => true,
            'auto_increment' => true,
        ],
        'name' => [
            'title' => E::ts('Name'),
            'sql_type' => 'varchar(255)',
            'input_type' => 'Text',
            'description' => E::ts('Webhook name'),
            'default' => null,
            'usage' => [
                'import',
                'export',
                'duplicate_matching',
            ],
        ],
        'description' => [
            'title' => E::ts('Description'),
            'sql_type' => 'text',
            'input_type' => 'Text',
            'description' => E::ts('Webhook description'),
            'default' => null,
            'usage' => [
                'import',
                'export',
                'duplicate_matching',
            ],
        ],
        'handler' => [
            'title' => E::ts('Handler'),
            'sql_type' => 'varchar(255)',
            'input_type' => 'Text',
            'description' => E::ts('Handler class'),
            'default' => null,
            'usage' => [
                'import',
                'export',
                'duplicate_matching',
            ],
        ],
        'query_string' => [
            'title' => E::ts('Query string'),
            'sql_type' => 'varchar(255)',
            'input_type' => 'Text',
            'description' => E::ts('Webhook query parameter'),
            'default' => null,
            'usage' => [
                'import',
                'export',
                'duplicate_matching',
            ],
        ],
        'processor' => [
            'title' => E::ts('Processor'),
            'sql_type' => 'varchar(255)',
            'input_type' => 'Text',
            'description' => E::ts('Processor class'),
            'default' => null,
            'usage' => [
                'import',
                'export',
                'duplicate_matching',
            ],
        ],
        'options' => [
            'title' => E::ts('Custom options'),
            'sql_type' => 'text',
            'input_type' => 'Text',
            'description' => E::ts('Custom serialized data for PHP'),
            'default' => null,
            'serialize' => constant('CRM_Core_DAO::SERIALIZE_PHP'),
            'usage' => [
                'import',
                'export',
                'duplicate_matching',
            ],
        ],
    ],
];
