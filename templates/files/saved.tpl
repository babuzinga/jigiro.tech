{include file="layout/header.tpl"}

<section>
  <div class="content">
    {if !empty($medias)}
      <h1>Альбом <span>Все {$cnt}</span></h1>
      <div id="media-container" class="collection">
        {include file="files/items.tpl" upload=true}
      </div>

      {include file="blocks/preloader.tpl"}
    {else}
      <h1>Альбом пуст</h1>
    {/if}
  </div>
</section>

{include file="layout/footer.tpl"}