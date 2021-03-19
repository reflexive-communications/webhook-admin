<div id="webhook-main-wrapper" class="crm-container">
    <div class="crm-block crm-form-block">
        <div class="action-link">
            <a class="button new-option crm-popup webhook-action" href="{$newItemForm}">
                <span><i class="crm-i fa-plus-circle"></i> {ts}Add New Webhook{/ts}</span>
            </a>
        </div>
    </div>

    <h3>Webhooks</h3>
    <div class="crm-search-results">
        {include file="CRM/common/jsortable.tpl"}
        <table id="webhook-routes" class="row-highlight display">
            <thead class="sticky">
            <tr>
                <th id="sortable">{ts}ID{/ts}</th>
                <th id="sortable">{ts}Name{/ts}</th>
                <th id="sortable">{ts}Selector{/ts}</th>
                <th id="sortable">{ts}Handler{/ts}</th>
                <th id="sortable">{ts}Description{/ts}</th>
                <th id="sortable">{ts}Label{/ts}</th>
                <th id="sortable">{ts}Actions{/ts}</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$webhooks key=id item=webhook}
                <tr class="crm-entity {cycle values="odd-row,even-row"}">
                    <td>{$id}</td>
                    <td>{$webhook.name}</td>
                    <td>{$webhook.selector}</td>
                    <td>{$webhook.handler}</td>
                    <td>{$webhook.description}</td>
                    <td>{$webhook.label}</td>
                    <td>{$webhook.actions}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>
