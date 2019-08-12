
{if !empty($date_array)}
<div class="date-select date-{$name}">
  <table>
  <tr>
    {if !empty($desc)}<td class="desc">{$desc}:</td>{/if}
    <td class="value none-select" data-name="{$name}">{$current_day}</td>
  </tr>
  </table>

  <input type="text" name="{$name}" value="{$current_day}">

  <div class="shadow-page">
    <div>
      <p class="month-title">{$month_title}</p>

      <ul>
      {foreach from=$date_array key=value item=item}
        <li
          data-value="{$value}"
          data-name="{$name}"
          class="{if !empty($item.class)}{$item.class}{/if}{if $current_day==$value} current{/if}"
          >{$item.date}
        </li>
      {/foreach}
      </ul>

      <span class="close-shadow-page link">Закрыть</span>
    </div>
  </div>
</div>
{/if}


