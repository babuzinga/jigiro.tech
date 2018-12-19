{literal}
  <script id="media-template" type="text/x-handlebars-template">
    {{!--
    <div>
      <div>
        <span id="insta-owner">@{{owner_login}}</span>
      </div>
      <div>
        <span onclick="copyToClipboard('#insta-owner')" class="link">Копировать автора</span>
        &mdash;
        <a href="https://www.instagram.com/{{owner_login}}" rel="nofollow" target="_blank">Открыть страницу</a>
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
    --}}



    {{#each medias}}
    <div>
      <div>
        <ul class="media-control">
          <li>
            <a
              href="/files/download/?url={{this.url}}&video={{this.isVideo}}"
              rel="nofollow"
              target="_blank"
              >Скачать</a>
          </li>
          {/literal}{if !empty($current_user)}{literal}
            <li>
              <span onclick="saveMedia('{{this.isVideo}}', '{{this.url}}', this)" class="link">Сохранить</span>
            </li>
          {/literal}{/if}{literal}
        </ul>
      </div>

      <div>
        {{#if this.isVideo}}
          <video controls src="{{this.url}}"></video>
        {{else}}
          <img src="{{this.url}}"/>
        {{/if}}
      </div>
    </div>
    {{/each}}
  </script>
{/literal}