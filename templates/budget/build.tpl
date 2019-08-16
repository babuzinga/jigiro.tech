<form onsubmit="saveBudget(); return false;" class="budget_day">
  <h2>График расходов</h2>

  <br/>

  {if !empty($error)}
    <div class="error">
      {$error}
    </div>
  {else}
    {* Сохранение изменении *}
    <button 
      type="button" 
      class="button" 
      id="submit_button" 
      onclick="saveBudget()"
      >
      Сохранить
    </button>

    {* Скрытые значения Бюджета *}
    <input type="hidden" value="{$budgetid}" name="budgetid">
    <input type="hidden" value="{$amount_money}" name="amount_money">
    <input type="hidden" value="{$dt_start}" name="dt_start">
    <input type="hidden" value="{$dt_end}" name="dt_end">

    {* Период *}
    <div class="budget-period">
      {$dt_start|date_format:"%d-%m-%Y"} - {$dt_end|date_format:"%d-%m-%Y"}
    </div>

    {* Источники бюджета *}
    {if !empty($budget_source)}
    <div class="data-block">
      <span class="title">Деньги :</span>
      <table class="value-table">
        <tbody id="budget_money">
          {foreach from=$budget_source key=value item=item}
          <tr>
            {assign var="sn" value=$value+1}
            <td>{$sn}.</td>
            <td>
              <input 
                type="text" 
                placeholder="Источник"
                class="form-text" 
                value="{$item.budget_name_source}"
                name="budget_name_source[]"
              >
            </td>
            <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
            <td>
              <input 
                type="text" 
                placeholder="Сумма"
                value="{$item.budget_amount_source}"
                class="form-text" 
                name="budget_amount_source[]"
              >
            </td>
            <td>
              {if $sn eq 1}
                <span 
                  class="link" 
                  onclick="addValueRow('budget_money', 'budget-row-template-source')"
                  >
                  [N]
                </span>
              {else}
                <span>[R]</span>
              {/if}
            </td>
          </tr>
          {/foreach}
        </tbody>
      </table>
    </div>
    {/if}

    {* Сводная *}
    <table class="v-blind">
      <thead class="none-select">
        <tr>
          <td colspan="5">Сводная</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="2">Сумма</td>
          <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
          <td colspan="2">{$amount_money} руб.</td>
        </tr>
        <tr>
          <td colspan="2">Лимит в день</td>
          <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
          <td colspan="2">{$expense} руб.</td>
        </tr>
        <tr>
          <td colspan="2">Период</td>
          <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
          <td colspan="2">{$days}</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5">
            {$amount_money} руб. / Потрачено / Осталось / Период - {$days}
          </td>
        </tr>
      </tfoot>
    </table>

    {* Затраты по дням *}
    {if !empty($budget_day)}
      {foreach from=$budget_day key=value item=item}
      <table class="value-table v-blind{if $item.date eq $current_day} current down{/if}">
        <thead class="none-select">
          <tr>
            <td colspan="5">
              {$item.date} / Потрачено 0 
            </td>
          </tr>
        </thead>

        <tbody id="budget_{$item.date}">
          {foreach from=$item.expenses key=subvalue item=subitem}
          <tr>
            {assign var="sn2" value=$subvalue+1}
            <td>{$sn2}.</td>
            <td>
              <input 
                type="text" 
                placeholder="Расходы"
                class="form-text" 
                value="{$subitem.where}"
                name="budget_where_{$item.date}[]"
              >
            </td>
            <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
            <td>
              <input 
                type="text" 
                placeholder="Сумма"
                value="{$subitem.amount}" 
                class="form-text" 
                name="budget_amount_{$item.date}[]"
              >
            </td>
            <td>
              {if $sn2 eq 1}
                <span 
                  class="link" 
                  onclick="addValueRow('budget_{$item.date}', 'budget-row-template', {literal}{ dt: {/literal}'{$item.date}'{literal} }{/literal})"
                  >
                  [N]
                </span>
              {else}
                <span>[R]</span>
              {/if}
            </td>
          </tr>
          {/foreach}
        </tbody>
      </table>
      {/foreach}
    {/if}

    {* Сохранение изменении *}
    <button 
      type="button" 
      class="button" 
      id="submit_button" 
      onclick="saveBudget()"
      >
      Сохранить
    </button>

    {* Подключение шаблонов *}
    {include file="handlebars/budget-row-template.tpl"}
    {include file="handlebars/budget-row-template-source.tpl"}
  {/if}
</form>
