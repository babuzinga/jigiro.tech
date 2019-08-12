{include file="layout/header.tpl"}

<section>
  <div class="content">
    <h2>Сервисы</h2>
    <p>Список всех доступных сервисов проекта</p>
    <br/>
    {if !empty($services)}
    <ul class="data-list">
      {foreach from=$services key=key item=service}
      <li>
        <a href="{$host_name}{$key}">{$service}</a>
      </li>
      {/foreach}
    </ul>
    {/if}
  </div>
</section>

{include file="layout/footer.tpl"}