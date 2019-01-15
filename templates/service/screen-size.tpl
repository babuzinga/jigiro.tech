{include file="layout/header.tpl"}

<section>
  <div class="content">
    Разрешение экрана в пикселях: <span id="size_monitor"></span>
    <br/>
    Разрешение экрана браузера в пикселях: <span id="size_browser"></span>
    <br/>
    Дополнительная информация:
    <br/>
    <b>{$user_ip}</b>
    <br/>
    <b>{$user_agent}</b>
    <br/>
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