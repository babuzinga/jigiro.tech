<div
  id="{if empty($id_share)}the_share{else}{$id_share}{/if}"
  class="ya-share2"
  data-services="vkontakte,facebook,odnoklassniki,moimir,twitter{if isset($google_plus)},gplus{/if}{if !empty($smartphone)},telegram,viber,whatsapp{/if}"
  ></div>

{* https://tech.yandex.ru/share/doc/dg/add-docpage/ *}
<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js" charset="utf-8"></script>
<script>
    var share = Ya.share2('{if empty($id_share)}the_share{else}{$id_share}{/if}', {
        content: {
            url: '{$selfurl}',
            title: '{$title|default}',
            description: '{$description|default}',
            image: '{$image|default}'
        }
    });
</script>