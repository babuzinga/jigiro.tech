{foreach $medias as $media}
  {include file="files/item.tpl"}
{/foreach}

{if !empty($upload) && $current_page < $page_count}
  <br/>
  {assign var=next_page value=$current_page+1}

  <button
    type="button"
    class="button"
    onclick="uploadMoreItems('{$current_url}?page={$next_page}&mode=upload', this)"
    >Загрузить еще</button>
{/if}