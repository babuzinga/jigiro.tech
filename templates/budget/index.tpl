{include file="layout/header.tpl"}

<section>
  <div class="content">
    <form onsubmit="buildBudget(); return false;" id="build-budget">
      {Model_Budget::getSavedBudget()}

      <h2>График расходов</h2>

      <div class="budget-period">
        {myblock 
          handler="Controller_Blocks.blockSetDate" 
          name='dt_start' 
          desc='Выберите начало периода'
        }
        {myblock 
          handler="Controller_Blocks.blockSetDate" 
          name='dt_end' 
          desc='Выберите конец периода'
        }
      </div>

      <button 
        type="button" 
        class="button" 
        id="submit_button" 
        onclick="buildBudget()"
        >Сформировать</button>

      <div id="error" class="error hidden"></div>
    </form>

    <br/>

    {include file="blocks/preloader.tpl"}
  </div>

  {include file="handlebars/variable-row-template.tpl"}
</section>

{include file="layout/footer.tpl"}