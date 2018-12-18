{include file="layout/header.tpl"}

<section>
  <div class="content">
    <h1>Скачать изображения и видео из <span>Instagram</span></h1>
    <br/>
    <form onsubmit="uploadMediaInsta(); return false;">
      <p>
        Сервис позваляет скачать фото и видео из Instagram онлайн. Для скачивания
        необходимо указать ссылку на пост в Instagram, нажать кнопку &laquo;Загрузить&raquo; и
        получить нужные фотографии или видео. Скачивание бесплатно и не требует регистарции.
      </p>
      <br/>
      <input
        type="text"
        placeholder="Ссылка на пост в Instagram"
        id="instagram_media_page_url"
        class="form-text"
        value="https://www.instagram.com/p/Brgdg_8HDMf/"
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

    <div class="spinner" id="preloader">
      <div class="double-bounce1"></div>
      <div class="double-bounce2"></div>
    </div>

    <div id="success" style="display:none;">
      <div id="media-container"></div>
    </div>
  </div>

  {include file="handlebars/media-template.tpl"}
</section>

<section>
  <div class="share-block">
    <span>Рассказать друзьям, как копировать фото, видео и текст из Instagram</span>
    {include
      inline file="blocks/ya-share.tpl"
      id_share="ins-share"
      smartphone=$smartphone
      title="На сайте Jigiro.tech вы можете скопировать текст, скачать фото и видео из Instagram онлайн."
      description=""
      image=""
    }
  </div>
</section>

{include file="layout/footer.tpl"}