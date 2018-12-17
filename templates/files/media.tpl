<div id="media-{$media->id}">
  <div>
    <span class="s-name">{$media->title}</span>
    <br/>
    <span class="link">Скачать</span>
    &mdash;
    <span class="s-hidden">Переименовать</span>
    &mdash;
    <span class="s-hidden">Переместить в группу</span>
    &mdash;
    <span class="link" onclick="removeMedia({{$media->id}})">Удалить</span>
  </div>
  <div>
    {if !empty($media->isVideo)}
      <video controls src="{$media->getUrl()}"></video>
    {else}
      <img src="{$media->getUrl()}"/>
    {/if}
  </div>
</div>