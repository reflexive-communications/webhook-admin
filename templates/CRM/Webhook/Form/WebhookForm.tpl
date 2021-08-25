<div class="crm-block crm-form-block">
    <table class="form-layout">
        <tr>
            <td class="label">{$form.name.label}</td>
            <td class="content">{$form.name.html}<br/>
                <span class="description">{ts}Name of the webhook{/ts}</span>
            </td>
        </tr>
        <tr>
            <td class="label">{$form.query_string.label}</td>
            <td class="content">{$form.query_string.html}<br/>
                <span class="description">{ts}Query string - routing is based on this parameter{/ts}</span>
            </td>
        </tr>
        <tr>
            <td class="label">{$form.processor.label}</td>
            <td class="content">{$form.processor.html}<br/>
                <span class="description">{ts}Class name of the processor that will use to handle this request{/ts}</span>
            </td>
        </tr>
        <tr>
            <td class="label">{$form.handler.label}</td>
            <td class="content">{$form.handler.html}<br/>
                <span class="description">{ts}Class name of the handler that will handle this request{/ts}</span>
            </td>
        </tr>
        <tr>
            <td class="label">{$form.description.label}</td>
            <td class="content">{$form.description.html}<br/>
                <span class="description">{ts}Description of the webhook{/ts}</span>
            </td>
        </tr>
    </table>
    {if isset($hook_url)}
    <div>
        <p><b>{$hook_url}</b></p>
    </div>
    {/if}
    <div class="crm-submit-buttons">
        {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>
</div>
