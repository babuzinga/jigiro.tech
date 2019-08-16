{include file="layout/header.tpl"}

<section>
  <div class="content">
    {*<h1><span>JIGIRO</span> SERVICE</h1>*}
    <p>
      Сервис позваляет скачать фото и видео из Instagram онлайн. Для скачивания
      необходимо указать ссылку на пост в Instagram, нажать кнопку &laquo;Загрузить&raquo; и
      получить нужные фотографии или видео. Скачивание бесплатно и не требует регистрации.
    </p>

    <br/>

    <form onsubmit="uploadMediaInsta(); return false;">
      <input
        type="text"
        placeholder="Ссылка на пост в Instagram"
        id="instagram_media_page_url"
        class="form-text"
        value=""
        onclick="this.select();"
        >
      <div id="error" class="error hidden"></div>
      <button
        type="button"
        class="button"
        id="submit_button"
        onclick="uploadMediaInsta()"
        >Загрузить</button>
    </form>

    {include file="blocks/ajax-response.tpl"}
  </div>

  {include file="handlebars/media-template.tpl"}
</section>

<section>
  <div class="share-block">
    <span>Рассказать друзьям, как скачать фото и видео из Instagram</span>
    {include
      inline file="blocks/ya-share.tpl"
      id_share="ins-share"
      smartphone=$smartphone
      title="На сайте Jigiro.tech вы можете скачать фото и видео из Instagram онлайн."
      description=""
      image=""
    }
  </div>
</section>

{include file="layout/footer.tpl"}