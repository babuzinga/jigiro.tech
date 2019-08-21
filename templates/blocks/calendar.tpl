<div>
  <p class="year-title">{$year}</p>
  <p class="month-title">
    <span class="link none-select" onclick="update_calendar(this, {$prev_month}, '{$name}')">{$months_title[$prev_month-1]}</span>
    {$months_title[$month-1]}
    <span class="link none-select" onclick="update_calendar(this, {$next_month}, '{$name}')">{$months_title[$next_month-1]}</span>
  </p>

  <ul>
  {foreach from=$date_array key=value item=item}
    <li
      onclick="selectDateInCalendar('{$name}', '{$value}', this)"
      class="{if !empty($item.class)} {$item.class}{/if}{if $current_day==$value} current{/if}"
      >{$item.date}
    </li>
  {/foreach}
  </ul>

  <span class="close-shadow-page link">Закрыть</span>
</div>