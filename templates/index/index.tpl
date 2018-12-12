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
      <div id="double-click" ondblclick=""></div>
      <input
        type="text"
        placeholder="Ссылка на пост в Instagram"
        id="instagram_media_page_url"
        class="form-control"
        value=""
        size="50"
        >
      <button
        type="button"
        id="submit_button"
        onclick="uploadMediaInsta()"
        >Загрузить</button>
    </form>

    <div class="spinner" id="preloader">
      <div class="double-bounce1"></div>
      <div class="double-bounce2"></div>
    </div>

    <div id="error" style="display: none; color: red; margin: 20px 0;">

    </div>
    <div id="success" style="display:none;">
      <div id="media-container"></div>
    </div>

    <br/>
    <br/>
    <br/>
    <div class="ins-share">
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
  </div>

  {include file="handlebars/media-template.tpl"}
</section>

{include file="layout/footer.tpl"}