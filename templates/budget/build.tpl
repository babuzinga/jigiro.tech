<form onsubmit="saveBudget(); return false;" class="budget-day" id="build-budget">
  {Model_Budget::getSavedBudget()}
  
  <h2>График расходов</h2>

   {* Период *}
  <div class="budget-period">
    {myblock 
      handler="Controller_Blocks.blockSetDate" 
      name='dt_start' 
      desc='Начало периода' 
      value=$budget->dt_start
    }
    {myblock 
      handler="Controller_Blocks.blockSetDate" 
      name='dt_end' 
      desc='Конец периода' 
      value=$budget->dt_end
    }
  </div>

  {* Блок в вотором будут обновлятся данные после нажатия "Сохранить" *}
  <div id="success">
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
        >Сохранить</button>

      <label>
        <input 
          type="text" 
          value="{$budget->name}" 
          name="name"
          class="form-text"
          placeholder="Название"
        >
      </label>

      {* Скрытые значения Бюджета *}
      <input type="hidden" value="{$budget->hash}" name="hash">
      <input type="hidden" value="{$budget->days}" name="days">
      <input type="hidden" value="{$budget->amount}" name="amount">
      <input type="hidden" value="{$budget->balance}" name="balance">
      <input type="hidden" value="{$budget->expense}" name="expense">

      {* Источники бюджета *}
      {assign var="budget_source" value=$budget->getSourceData()}
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
            <td colspan="2">{$budget->amount} руб.</td>
          </tr>
          <tr>
            <td colspan="2">Лимит в день</td>
            <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
            <td colspan="2">{$budget->expense} руб.</td>
          </tr>
          <tr>
            <td colspan="2">Потрачено</td>
            <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
            <td colspan="2">{$budget->amount - $budget->balance}  руб.</td>
          </tr>
          <tr>
            <td colspan="2">Остаток</td>
            <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
            <td colspan="2">{$budget->balance} руб.</td>
          </tr>
          <tr>
            <td colspan="2">Период</td>
            <td>&nbsp;&nbsp;-&nbsp;&nbsp;</td>
            <td colspan="2">{$budget->days}</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5">
              Остаток : {$budget->balance} руб.
              / 
              Лимит в день : {$budget->expense} руб. 
            </td>
          </tr>
        </tfoot>
      </table>

      {* Затраты по дням *}
      {assign var="budget_costs" value=$budget->getCostsData()}
      {if !empty($budget_costs)}
        {foreach from=$budget_costs key=key item=item}
        <table class="value-table v-blind{if $key eq $current_day} current down{/if}">
          <thead class="none-select">
            <tr>
              <td colspan="5">
                {$key} / Потрачено {$budget->costs.$key._total} руб.
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
        >Сохранить</button>

      {* Подключение шаблонов для ajax-добавления/подгрузки данных *}
      {include file="handlebars/budget-row-template.tpl"}
      {include file="handlebars/budget-row-template-source.tpl"}
    {/if}
  </div>
</form>
