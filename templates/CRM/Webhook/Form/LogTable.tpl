<div id="webhook-main-wrapper" class="crm-container">
    <div class="crm-search-results">
        {include file="CRM/common/jsortable.tpl"}
        <table id="webhook-routes" class="row-highlight display">
            <thead class="sticky">
            <tr>
                <th id="sortable">{ts}id{/ts}</th>
                <th id="sortable">{ts}timestamp{/ts}</th>
                <th id="sortable">{ts}raw{/ts}</th>
                <th id="sortable">{ts}get{/ts}</th>
                <th id="sortable">{ts}post{/ts}</th>
                <th id="sortable">{ts}headers{/ts}</th>
                <th id="sortable">{ts}error{/ts}</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$logs key=id item=entry}
                <tr class="crm-entity {cycle values="odd-row,even-row"}">
                    <td>{$id}</td>
                    <td>{$entry.timestamp}</td>
                    <td>{$entry.data.raw}</td>
                    <td>
                        <ul>
                        {foreach from=$entry.data.get key=gid item=getentry}
                            <li>{$getentry}</li>
                        {/foreach}
                        <ul>
                    </td>
                    <td>
                        <ul>
                        {foreach from=$entry.data.post key=pid item=postentry}
                            <li>{$postentry}</li>
                        {/foreach}
                        <ul>
                    </td>
                    <td>
                        <ul>
                        {foreach from=$entry.data.header key=hid item=headerentry}
                            <li>{$hid} : {$headerentry}</li>
                        {/foreach}
                        <ul>
                    </td>
                    <td>
                        <ul>
                        {foreach from=$entry.data.error key=eid item=errorentry}
                            <li>{$errorentry}</li>
                        {/foreach}
                        <ul>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    <div class="crm-submit-buttons">
        {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>
</div>
