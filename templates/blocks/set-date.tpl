
{if !empty($date_array)}
<div class="date-select date-{$name}">
  <table>
  <tr>
    {if !empty($desc)}<td class="desc">{$desc}:</td>{/if}
    <td class="data-value none-select" data-name="{$name}">{$current_day}</td>
  </tr>
  </table>

  <input type="text" name="{$name}" value="{$current_day}">

  <div class="shadow-page">
    {include file="blocks/calendar.tpl"}
  </div>
</div>
{/if}


