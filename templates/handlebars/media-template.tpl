{literal}
  <!-- Mustache template -->
  <script id="media-template" type="text/x-handlebars-template">
    <div class="row">
      <div class="col">
        <span id="owner" class="owner-name">@{{owner_login}}</span>
      </div>
      <div class="col-2">
        <span onclick="copyToClipboard('#owner')" class="link">Копировать владельца</span>
        &mdash;
        <a href="https://www.instagram.com/{{owner_login}}" target="_blank">Открыть страницу</a>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <p id="caption">{{caption}}</p>
      </div>
      <div class="col-2">
        <span onclick="copyToClipboard('#caption')" class="link">Копироват текст</span>
      </div>
    </div>

    {{#each medias}}
    <div class="row">
      <div class="col">
        {{#if this.isVideo}}
        <video controls src="{{this.url}}"></video>
        {{else}}
        <img class="img-fluid" src="{{this.url}}"/>
        {{/if}}
      </div>

      <div class="col-2">
        <a href="{{this.url}}" download>Скачать</a>
        &mdash;
        <span onclick="saveMedia('{{this.isVideo}}', '{{this.url}}')" class="link">Сохранить</span>
      </div>
    </div>
    {{/each}}
  </script>
{/literal}