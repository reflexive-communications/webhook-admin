<?php

use Civi\Api4\Webhook;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_WebhookDelete extends CRM_Webhook_Form_WebhookBase
{
    /**
     * @return void
     * @throws \API_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function buildQuickForm(): void
    {
        parent::buildQuickForm();

        $this->addButtons(
            [
                [
                    'type' => 'cancel',
                    'name' => ts('Cancel'),
                ],
                [
                    'type' => 'done',
                    'name' => ts('Delete'),
                    'isDefault' => true,
                ],
            ]
        );
        $webhook = Webhook::get(false)
            ->addWhere('id', '=', $this->id)
            ->setLimit(1)
            ->execute()
            ->first();
        $this->assign('hookName', $webhook['name']);
        $this->assign('hookQueryString', $webhook['query_string']);

        $this->setTitle(ts('Webhook Delete'));
    }

    /**
     * @return void
     * @throws \API_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function postProcess(): void
    {
        parent::postProcess();
        Webhook::delete(false)
            ->addWhere('id', '=', $this->id)
            ->setLimit(1)
            ->execute();

        // Show success
        CRM_Core_Session::setStatus(ts('Webhook deleted'), 'Webhook', 'success', ['expires' => 5000]);
    }
}
