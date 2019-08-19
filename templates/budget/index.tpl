{include file="layout/header.tpl"}

<section>
  <div class="content">
    {if !empty($budget_save)}
      <p>
        Сохраненные
        {foreach from=$budget_save key=key item=item}
          <br/><a href="/budget/show/{$item['hash']}/">{$item.dt_start|date_format:"%d-%m-%Y"} - {$item.dt_end|date_format:"%d-%m-%Y"}</a> - Удалить
        {/foreach}
      </p>
      <br/>
    {/if}

    <form onsubmit="buildBudget(); return false;" id="build-budget">
      <h2>График расходов</h2>

      <div class="budget-period">
        {myblock handler="Controller_Blocks.blockSetDate" name='dt_start' desc='Выберите начало периода'}
        {myblock handler="Controller_Blocks.blockSetDate" name='dt_end' desc='Выберите конец периода'}
      </div>

      <div id="error" class="error hidden"></div>
      <button 
        type="button" 
        class="button" 
        id="submit_button" 
        onclick="buildBudget()"
        >Сформировать</button>
    </form>

    <br/>

    {include file="blocks/preloader.tpl"}
  </div>

  {include file="handlebars/variable-row-template.tpl"}
</section>

{include file="layout/footer.tpl"}