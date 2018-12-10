{if !empty($note)}
  {foreach from=$note item=subitem}
    {include file="note/item.tpl"}
  {/foreach}
{else}
  <p>Список пуст</p>
{/if}