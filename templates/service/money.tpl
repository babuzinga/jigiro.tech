{include file="layout/header.tpl"}

<section>
  <div class="content">
    <p>
      Составления плана ежедневных затрат
    </p>

    <br/>

    <form onsubmit="buildCalculation(); return false;">
      <input 
        type="text" 
        placeholder="Сумма"
        id="amount_money"
        class="form-text"
        value="3000"
        onclick="this.select();"
        >
      
      {myblock handler="Controller_Blocks.blockSetDate" name='dt_start' desc='Выберите начало периода'}
      {myblock handler="Controller_Blocks.blockSetDate" name='dt_end' desc='Выберите конец периода'}

      <div id="error" class="error hidden"></div>
      <button 
        type="button" 
        class="button" 
        id="submit_button" 
        onclick="buildCalculation()"
        >Расчитать</button>
    </form>

    {include file="blocks/ajax-response.tpl"}
  </div>

  {include file="handlebars/variable-row-template.tpl"}
</section>

{include file="layout/footer.tpl"}