{literal}
  <!-- Mustache template -->
  <script id="media-template" type="text/x-handlebars-template">
    <div>
      <div>
        <span id="insta-owner" class="owner-name">@{{owner_login}}</span>
      </div>
      <div>
        <span onclick="copyToClipboard('#insta-owner')" class="link">Копировать автора</span>
        &mdash;
        <a href="https://www.instagram.com/{{owner_login}}" target="_blank">Открыть страницу</a>
      </div>
    </div>

    <div>
      <div>
        <p id="insta-caption">{{caption}}</p>
      </div>
      <div>
        <span onclick="copyToClipboard('#insta-caption')" class="link">Копировать описание</span>
      </div>
    </div>

    {{#each medias}}
    <div>
      <div>
        <a href="{{this.url}}" download>Скачать</a>
        &mdash;
        <span onclick="saveMedia('{{this.isVideo}}', '{{this.url}}')" class="link">Сохранить</span>
      </div>

      <div>
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