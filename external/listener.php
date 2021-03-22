<?php

require_once '../civicrm.config.php';

CRM_Core_Config::singleton();

if (defined('PANTHEON_ENVIRONMENT')) {
    ini_set('session.save_handler', 'files');
}

$dpc = new CRM_Webhook_Dispatcher();
$dpc->run();
