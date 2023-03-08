<?php

use CRM_Webhook_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_LogTable extends CRM_Core_Form
{
    /**
     * Configdb
     *
     * @var CRM_Webhook_Config
     */
    protected $config;

    /**
     * Preprocess form
     *
     * @throws CRM_Core_Exception
     */
    public function preProcess()
    {
        // Get current settings
        $this->config = new CRM_Webhook_Config(E::LONG_NAME);
        $this->config->load();
    }

    /**
     * Build form
     */
    public function buildQuickForm()
    {
        parent::buildQuickForm();

        // get the current configuration object
        $config = $this->config->get();
        foreach ($config["logs"] as $k => $v) {
            $config["logs"][$k]["timestamp"] = date("Y-m-d H:i:s", $config["logs"][$k]["timestamp"]);
        }

        // Export logs to template
        $this->assign("logs", $config["logs"]);

        // Submit buttons
        $this->addButtons(
            [
                [
                    "type" => "done",
                    "name" => ts("Delete logs"),
                    "isDefault" => true,
                ],
            ]
        );
        $this->setTitle(ts("Webhook Logs"));
    }

    /**
     * Post process
     */
    public function postProcess()
    {
        parent::postProcess();
        if (!$this->config->deleteLogs()) {
            CRM_Core_Session::setStatus(ts("Error during log deletion"), "Webhook", "error");

            return;
        }
        CRM_Core_Session::setStatus(ts("Webhook logs deleted."), "Webhook", "success", ["expires" => 5000,]);
    }
}
