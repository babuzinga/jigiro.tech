{include file="layout/header.tpl"}

<section>
  <div class="content">
    {if !empty($medias)}
      <h1>Сохраненное: <span>{$current_user->login}</span></h1>
      <br/>
      <div id="media-container">
        {foreach $medias as $media}
          {include file="files/media.tpl"}
        {/foreach}
      </div>
    {else}
      <h1>Данных нет</h1>
    {/if}
  </div>
</section>

{include file="layout/footer.tpl"}