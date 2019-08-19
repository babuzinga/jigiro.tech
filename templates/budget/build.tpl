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
    <input type="hidden" value="{$budget_data.hash}" name="hash">
    <input type="hidden" value="{$budget_data.dt_start}" name="dt_start">
    <input type="hidden" value="{$budget_data.dt_end}" name="dt_end">
    <input type="hidden" value="{$budget_data.days}" name="days">
    <input type="hidden" value="{$budget_data.amount}" name="amount">
    <input type="hidden" value="{$budget_data.balance}" name="balance">
    <input type="hidden" value="{$budget_data.expense}" name="expense">

    {* Период *}
    <div class="budget-period">
      {$budget_data.dt_start|date_format:"%d-%m-%Y"}
      -
      {$budget_data.dt_end|date_format:"%d-%m-%Y"}
    </div>

    {* Источники бюджета *}
    {if !empty($budget_source)}
    <div class="data-block">
      <span class="title">Деньги :</span>
      <table class="value-table">
        <tbody id="budget_source">
          {assign var="sn" value=0}
          {foreach from=$budget_source key=value item=item}
          <tr>
            {assign var="sn" value=$sn+1}
            <td>{$sn}.</td>
            <td>
              <input 
                type="text" 
                placeholder="Источник"
                class="form-text" 
                value="{$item.name}"
                name="source_name[]"
              >
            </td>
            <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
            <td>
              <input 
                type="text" 
                placeholder="Сумма"
                value="{$item.amount}"
                class="form-text" 
                name="source_amount[]"
              >
            </td>
            <td>
              {if $sn eq 1}
                <span 
                  class="link" 
                  onclick="addValueRow('budget_source', 'budget-row-template-source')"
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
          <td colspan="2">{$budget_data.amount} руб.</td>
        </tr>
        <tr>
          <td colspan="2">Лимит в день</td>
          <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
          <td colspan="2">{$budget_data.expense} руб.</td>
        </tr>
        <tr>
          <td colspan="2">Потрачено</td>
          <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
          <td colspan="2">{$budget_data.amount - $budget_data.balance}  руб.</td>
        </tr>
        <tr>
          <td colspan="2">Остаток</td>
          <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
          <td colspan="2">{$budget_data.balance} руб.</td>
        </tr>
        <tr>
          <td colspan="2">Период</td>
          <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
          <td colspan="2">{$budget_data.days}</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="5">
            Остаток : {$budget_data.balance} руб.
            / 
            Лимит в день : {$budget_data.expense} руб. 
          </td>
        </tr>
      </tfoot>
    </table>

    {* Затраты по дням *}
    {if !empty($budget_costs)}
      {foreach from=$budget_costs key=key item=item}
      <table class="value-table v-blind{if $key eq $current_day} current down{/if}">
        <thead class="none-select">
          <tr>
            <td colspan="5">
              {$key} / Потрачено {$budget_data.costs.$key._total} руб.
            </td>
          </tr>
        </thead>

        <tbody id="budget_{$key}">
          {assign var="sn2" value=0}
          {foreach from=$item key=value2 item=item2}
          <tr>
            {assign var="sn2" value=$sn2+1}
            <td>{$sn2}.</td>
            <td>
              <input 
                type="text" 
                placeholder="Расходы"
                class="form-text" 
                value="{$item2.name}"
                name="costs_name__{$key}[]"
              >
            </td>
            <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
            <td>
              <input 
                type="text" 
                placeholder="Сумма"
                value="{$item2.amount}"
                class="form-text" 
                name="costs_amount__{$key}[]"
              >
            </td>
            <td>
              {if $sn2 eq 1}
                <span 
                  class="link" 
                  onclick="addValueRow('budget_{$key}', 'budget-row-template', {literal}{ dt: {/literal}'{$key}'{literal} }{/literal})"
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
