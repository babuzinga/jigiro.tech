<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>{if !empty($title)}{$title}{/if}</title>

  <link rel="icon" href="/public/img/icon-site.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/public/img/icon-site.ico" type="image/x-icon">

  <link rel="stylesheet" type="text/css" href="/public/css/reset.css" media="all">
  <link rel="stylesheet/less" type="text/css" href="/public/css/style.less?v=1.1" media="all">
  {* https://fontawesome.com/icons *}

  <!--[if lt IE 9]>
  <script type="text/javascript" src="/public/js/html5shim.js"></script>
  <script type="text/javascript" src="/public/js/respond.min.js"></script>
  <![endif]-->

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="{if !empty($description)}{$description}{/if}">
  <meta name="keywords" content="{if !empty($keywords)}{$keywords}{/if}">
  <meta name="author" content="{if !empty($author)}{$author}{/if}">

  <script src="/public/js/jquery-1.11.2.min.js"></script>
  <script src="/public/js/jquery-ui-1.11.4.min.js"></script>
  <script src="/public/js/script.js"></script>
  <script src="/public/js/less.min.js" type="text/javascript"></script>
  {* https://handlebarsjs.com/expressions.html *}
  <script src="/public/js/handlebars-v4.0.12.js" type="text/javascript"></script>

  <meta name="yandex-verification" content="358de140111a4632" />
</head>

{* https://html5book.ru/html-tags/ *}
<body>
{if !$localhost}
{literal}
  <!-- Yandex.Metrika counter -->
  <script type="text/javascript"> (function(m, e, t, r, i, k, a) {
      m[i] = m[i] || function() {
          (m[i].a = m[i].a || []).push(arguments)
        };
      m[i].l = 1 * new Date();
      k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
    ym(51914174, "init", {id: 51914174, clickmap: true, trackLinks: true, accurateTrackBounce: true}); </script>
  <noscript>
    <div><img src="https://mc.yandex.ru/watch/51914174" style="position:absolute; left:-9999px;" alt=""/></div>
  </noscript>
  <!-- /Yandex.Metrika counter -->
{/literal}
{/if}

  <main>
    <header>
      <div class="jigiro-logo">
        <a href="{$host_name}">
          <img src="{$host_name}/public/image/jigiro-logo.svg?v=2" alt="jigiro">
        </a>
      </div>

      <ul class="desktop">
        {include file="blocks/menu-items.tpl"}
      </ul>

      <div
        class="menu-wrapper"
        onclick="$('.hamburger-menu').toggleClass('animate');$('.mobile').toggleClass('hidden');"
        >
        <span class="hamburger-menu"></span>
      </div>

      <div class="mobile hidden">
        <ul>
          {include file="blocks/menu-items.tpl"}
        </ul>
      </div>
    </header>
