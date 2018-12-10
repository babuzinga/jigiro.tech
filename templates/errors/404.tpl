{include file="layout/header.tpl"}

<section>
  <div class="content error">
    <h2>404 error</h2>
    <p>{if !empty($reason)}{$reason}{/if}</p>
    <br/>
    <a href="{$host_name}">На главную</a>
  </div>
</section>

{include file="layout/footer.tpl"}