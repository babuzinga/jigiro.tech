{include file="layout/header.tpl"}

<section>
  <div class="content">
    <div class="c-body">
      <h1>Getting media with <span>Instagram</span></h1>
      <br/>
      <form>
        <input
          type="text"
          placeholder="Ссылка на страницу медиа Instagram"
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

      <div id="success" style="display:none;">
        <div id="media-container"></div>
      </div>

      <br/>
      <p>
        Сервис для сохранения изображений и видео из Instagram
      </p>
      &copy; babyzinga
    </div>
  </div>

  {literal}
  <!-- Mustache template -->
  <script id="media-template" type="text/x-handlebars-template">
    <div style="margin-top: 20px;" class="row">
      <div class="col-2">
        <button onclick="copyToClipboard('#owner')" type="button" class="btn btn-primary">Copy owner</button>
      </div>
      <div class="col">
        <h3 id="owner" class="text-left">{{owner_login}}</h3>
      </div>
    </div>

    <div class="row">
      <div class="col-2" style=" padding-top: 50px;">
        <button onclick="copyToClipboard('#caption')" type="button" class="btn btn-primary">Copy caption</button>
      </div>
      <div class="col" style="white-space: pre-wrap;">
        <p id="caption" class="text-left">{{caption}}</p>
      </div>
    </div>

    {{#each medias}}
    <div style="margin-top: 20px;" class="row justify-content-center">
      <div class="col">
        <a href="{{this.url}}" download>Скачать</a>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col">
        {{#if this.isVideo}}
          <video controls src="{{this.url}}"></video>
        {{else}}
          <img class="img-fluid" src="{{this.url}}"/>
        {{/if}}
      </div>
    </div>
    {{/each}}
  </script>
  {/literal}
</section>

{include file="layout/footer.tpl"}