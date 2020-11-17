<h1>GIPSY KINGS</h1>

<div id="custom_group">
  {* handle enable/disable actions*}
  {include file="CRM/common/enableDisableApi.tpl"}
  <table id="webhooks" class="row-highlight">
    <thead>
    <tr>
      <th>{ts}Name{/ts}</th>
      <th>{ts}Label{/ts}</th>
      <th>{ts}Description{/ts}</th>
      <th>{ts}Input format{/ts}</th>
      <th>{ts}Handler{/ts}</th>
      <th>{ts}Endpoint{/ts}</th>
      <th></th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$webhooks item=row}
      <tr id="CustomGroup-{$row.id}" data-action="setvalue" class="crm-entity {cycle values="odd-row,even-row"}">
        <td>{$row.name}</td>
        <td>{$row.comment}</td>
        <td>{$row.desc}</td>
        <td>{$row.processor}</td>
        <td>{$row.handler}</td>
        <td>{$row.path}</td>
      </tr>
    {/foreach}
    </tbody>
  </table>
