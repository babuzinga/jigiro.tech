<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>{if !empty($title)}{$title}{/if}</title>

  <link rel="icon" href="/public/img/icon-site.ico" type="image/x-icon">
  <link rel="shortcut icon" href="/public/img/icon-site.ico" type="image/x-icon">

  <link rel="stylesheet" type="text/css" href="/public/css/reset.css" media="all">
  <link rel="stylesheet/less" type="text/css" href="/public/css/style.less" media="all">
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
</head>

<body>
  <header>
    <ul class="desktop">
      {include file="blocks/menu-items.tpl"}
    </ul>

    <ul class="mobile">
      <li class="icon i-menu" onclick="$('.mobile').toggleClass('sub-menu')"></li>
      <li>
        <ul class="sub">
          <li class="icon i-close" onclick="$('.mobile').toggleClass('sub-menu')"></li>
          {include file="blocks/menu-items.tpl"}
        </ul>
      </li>
    </ul>
  </header>