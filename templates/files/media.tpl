<div id="media-{$media->id}">
  <div>
    <span class="s-name">{$media->title}</span>
    <br/>
    <a
      href="/files/download/?url={$media->link}&video={$media->video}&local=1"
      rel="nofollow"
      target="_blank"
      >Скачать</a>
    &mdash;
    <span class="s-hidden">Переименовать</span>
    &mdash;
    <span class="s-hidden">Переместить в группу</span>
    &mdash;
    <span class="link" onclick="removeMedia({{$media->id}})">Удалить</span>
  </div>
  <div>
    {if $media->video eq 1}
      <video controls src="{$media->getVideo()}"></video>
    {else}
      <img src="{$media->getImage()}"/>
    {/if}
  </div>
</div>