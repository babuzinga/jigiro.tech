{foreach $medias as $media}
  {include file="files/item.tpl"}
{/foreach}

{if !empty($upload) && $current_page < $page_count}
  <br/>
  {assign var=next_page value=$current_page+1}

  <input
    type="button"
    class="button"
    id="upload-item"
    data-url="{$current_url}?page={$next_page}&mode=upload"
    onclick="uploadMoreItems(this);"
    value="Загрузить еще"
    >
{/if}