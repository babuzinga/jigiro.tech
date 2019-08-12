{include file="layout/header.tpl"}

<section>
  <div class="content">
    <ul class="data-list">
      <li>
        Разрешение экрана в пикселях
        <span id="size_monitor"></span>
      </li>
      <li>
        Разрешение экрана браузера в пикселях
        <span id="size_browser"></span>
      </li>
      <li>
        IP-адрес
        <span>{$user_ip}</span>
      </li>
      <li>
        Заголовок браузера
        <span>{$user_agent}</span>
      </li>
    </ul>
  </div>
</section>

{literal}
  <script>
    var pixelRatio = window.devicePixelRatio.toPrecision(4);
    var width_a = screen.width;
    var height_a = screen.height;

    $('#size_monitor').html(screen.width + 'x' + screen.height);
    $('#size_browser').html($(window).width() + 'x' + $(window).height());
    $(window).on("resize", function() { $('#size_browser').html($(window).width() + 'x' + $(window).height()); });
  </script>
{/literal}

{include file="layout/footer.tpl"}