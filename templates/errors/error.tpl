{include file="layout/header.tpl"}

<section>
  <div class="content error">
    {if !empty($title)}<h2>{$title}</h2>{/if}
    {if !empty($reason)}<p>{$reason}</p>{/if}
    <br/>
    <a href="{$host_name}">На главную</a>
  </div>
</section>

{include file="layout/footer.tpl"}