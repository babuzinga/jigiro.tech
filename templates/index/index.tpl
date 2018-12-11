{include file="layout/header.tpl"}

<section>
  <div class="content">
    <h1>Скачать изображения и видео из <span>Instagram</span></h1>
    <br/>
    <form>
      <p>
        Сервис позваляет скачать фото и видео из Instagram онлайн. Для скачивания
        необхоимо указать ссылку на пост в Instagram, нажать кнопку &laquo;Загрузить&raquo; и
        получить нужные фотографии или видео. Скачивание бесплатно и не требует регистарции.
      </p>
      <br/>
      <input
        type="text"
        placeholder="Ссылка на пост в Instagram"
        id="instagram_media_page_url"
        class="form-control"
        value="https://www.instagram.com/p/BprckGJBJ2E/"
        size="50"
        >
      <button
        type="button"
        id="submit_button"
        class="btn btn-success"
        >Загрузить</button>
    </form>

    <div class="spinner" id="preloader">
      <div class="double-bounce1"></div>
      <div class="double-bounce2"></div>
    </div>

    <div id="error" style="display: none">

    </div>
    <div id="success" style="display:none;">
      <div id="media-container"></div>
    </div>

    <br/>
    <br/>
    &copy; babyzinga
  </div>

  {include file="handlebars/media-template.tpl"}
</section>

{include file="layout/footer.tpl"}