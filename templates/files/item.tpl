<div id="media-{$media->id}">
  <div>
    <span class="s-name">
      {$media->title}
      <br/>
      {$media->getFilesize()}{$media->getImageResolution(' - ')}
    </span>
    <ul class="media-control">
      <li>
        <a
          href="/files/download/?url={$media->link}&video={$media->video}&local=1"
          rel="nofollow"
          target="_blank"
          >Скачать</a>
      </li>
      <li>
        <span class="link" onclick="removeMedia({$media->id}, this)">Удалить</span>
      </li>
      {*
      <li><span class="s-hidden">Переименовать</span></li>
      <li><span class="s-hidden">Переместить в группу</span></li>
      *}
    </ul>
  </div>
  <div>
    {if $media->video eq 1}
      <video controls src="{$media->getUrl()}"></video>
    {else}
      <a href="{$media->getOriginalUrl()}" target="_blank">
        <img
          src="{$host_name}/public/image/preload-block.gif"
          data-desktop="{$media->getUrl('preview')}"
          data-mobile="{$media->getUrl('preview400')}"
          class="preview-image"
          alt="{$media->title}"
          />
      </a>
    {/if}
  </div>
</div>