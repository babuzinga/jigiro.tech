<form onsubmit="return false;">
  <h2>График расходов</h2>

  <br/>

  {if !empty($error)}
    <div class="error">
      {$error}
    </div>
  {else}
    <table class="v-blind down">
      <thead>
        <tr>
          <td colspan="2">Сводная</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Сумма</td>
          <td>{$amount_money} руб.</td>
        </tr>
        <tr>
          <td>Лимит в день</td>
          <td>{$expense} руб.</td>
        </tr>
        <tr>
          <td>Период</td>
          <td>{$days}</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2">
            {$amount_money} руб. - Остаток: {$expense} руб. - Прошло: {$days}
          </td>
        </tr>
      </tfoot>
    </table>
    
    {if !empty($calculation)}
      {foreach from=$calculation key=value item=item}

      <table class="v-blind{if $item.date eq $current_day} current{else} down{/if}">
        <thead>
          <tr>
            <td colspan="2">{$item.date}</td>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>Лимит</td>
            <td>{$item.limit}</td>
          </tr>
          <tr>
            <td>Расход</td>
            <td><input type="text" value="{$item.cons}" class="form-text"></td>
          </tr>
          <tr>
            <td>Баланс</td>
            <td>{$item.balance}</td>
          </tr>
        </tbody>
      </table>
      {/foreach}
    {/if}
  {/if}

  <br/>

  <button 
    type="button" 
    class="button" 
    id="submit_button" 
    onclick=""
    >Сохранить</button>
</form>
