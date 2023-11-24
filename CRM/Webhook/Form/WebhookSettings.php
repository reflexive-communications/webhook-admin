<?php

use Civi\Api4\WebhookLegacy;
use CRM_Webhook_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Webhook_Form_WebhookSettings extends CRM_Webhook_Form_WebhookBase
{
    /**
     * @return void
     * @throws \API_Exception
     * @throws \Civi\API\Exception\UnauthorizedException
     */
    public function buildQuickForm(): void
    {
        parent::buildQuickForm();

        // Add new route button
        $newItemForm = CRM_Utils_System::url('civicrm/admin/webhooks/form');
        $this->assign('newItemForm', $newItemForm);
        $this->assign('logTable', CRM_Utils_System::url('civicrm/admin/webhooks/logs'));

        $webhooks = WebhookLegacy::get(false)
            ->execute();
        // Add actions links
        foreach ($webhooks as $k => $hook) {
            $actions = array_sum(array_keys($this->links()));

            $webhooks[$k]['actions'] = CRM_Core_Action::formLink(
                $this->links(),
                $actions,
                ['id' => $hook['id']],
                ts('more'),
                false,
                'view.webhook.row',
                'Webhook',
                $hook['id'],
            );
        }
        // Export webhooks to template
        $this->assign('webhooks', $webhooks);

        $this->setTitle(ts('Webhook Settings'));

        // Add js functions. It seems the jquery $ sign and the smarty template $ sign conflicts.
        Civi::resources()->addScriptFile(E::LONG_NAME, 'js/popup.js');
    }

    /**
     * Get action Links.
     *
     * @return array Action links
     */
    public function links(): array
    {
        return [
            CRM_Core_Action::UPDATE => [
                'name' => ts('Edit'),
                'url' => 'civicrm/admin/webhooks/form',
                'qs' => 'id=%%id%%',
                'title' => ts('Edit webhook'),
                'class' => 'crm-popup webhook-action',
            ],
            CRM_Core_Action::DELETE => [
                'name' => ts('Delete'),
                'url' => 'civicrm/admin/webhooks/delete',
                'qs' => 'id=%%id%%',
                'title' => ts('Delete webhook'),
                'class' => 'crm-popup webhook-action',
            ],
        ];
    }
}
