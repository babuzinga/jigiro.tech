{include file="layout/header.tpl"}

<section>
  <div class="content">
    <p>
      Составления бюджета
    </p>

    <br/>

    <form onsubmit="buildBudget(); return false;">
      {myblock handler="Controller_Blocks.blockSetDate" name='dt_start' desc='Выберите начало периода'}
      {myblock handler="Controller_Blocks.blockSetDate" name='dt_end' desc='Выберите конец периода'}

      <div id="error" class="error hidden"></div>
      <button 
        type="button" 
        class="button" 
        id="submit_button" 
        onclick="buildBudget()"
        >Сформировать</button>
    </form>

    <br/>

    {include file="blocks/ajax-response.tpl"}
  </div>

  {include file="handlebars/variable-row-template.tpl"}
</section>

{include file="layout/footer.tpl"}