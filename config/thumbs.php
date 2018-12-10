<?php
// допустимые типы и размеры превьюшек
// например: "/data/cache/2009/1/1/123-48x48x.jpg"
$THUMB_SIZES = array(
  "review_preview" => "0x80",

  "item_preview_big" => "440x0",
  "item_preview_middle" => "210x155x",
  "item_square" => "210x210x",

  "kino_items" => "200x280x",

  "teaser" => "100x100x",
  "preview" => "100x0",
  "qa" => "100x0",
  "test100" => "100x0",
  "test200" => "200x0",
  "test_share" => "300x0",
  "maintop" => "630x400x",
  "main3" => "300x200x",
  "newsmain" => "135x80x",
  "last_news" => "150x170x",
  "flybox" => "80x60x",
  "article_main" => "250x0",
  "article_list" => "150x0",
  "article_share" => "160x120x",
  "informer" => "50x0",
  "news_list" => "150x130x",
  "zoom" => "640x480",
  "profile40" => "40x40x",
  "profile30" => "30x30x",
  "avatar_32" => "32x32x",
  "avatar_profile" => "120x120x",
  "avatar_big" => "64x64x",
  "avatar_edit" => "200x0",
  "avatar_small" => "48x48x",
  "community" => "100x0",
  "community_big" => "64x64x",
  "community_48" => "48x48x",
  "community_32" => "32x32x",
  "events_block" => "240x0",

  "shop_big" => "185x185x",
  "shop_small" => "32x32x",
  "shop" => "185x185x",
  "shop_edit" => "200x0",

  "bbthumb" => "64x64x",
  "thumb" => "500x0",
  "news_areaselect" => "300x0",
  "bigpicture" => "670x400x",
  "forum_teaser" => "180x110x",
  "new_photo" => "300x0",
  "photo_edit" => "100x0",
  "photo" => "640x480",
  "album_small" => "0x50",
  "album_thumb" => "50x40x",
  "album_index" => "158x106x",
  "photo_grid" => "120x90p",
  "album_cover" => "90x60c",
  "photo_export" => "300x300",
  "photo_export_big" => "650x650",
  "profile_bg" => "690x0",
  "schare" => "170x170x",

  "kino_preview_small" => "96x143x",
  "kino_preview" => "165x246x",
  "kinoteatr" => "458x0",
  "kinoteatr_preview" => "190x110x",
  "events_day" => "125x97x",
  "news_preview" => "470x170x",
  "news_preview_mail" => "300x220x",
  "place_preview_small" => "88x66x",
  "event_preview" => "265x152x",
  "main_event_preview" => "263x153x",
  "main_slider" => "556x402x",
  "main_news" => "556x201x"
);

// defaults - если нету своей картинки - показываем заглушку
// заглушка - статический файл лежащий в папке /i/
// при этом gender для профиля заменяем на пол - boy, girl
$THUMB_DEFAULTS = array(
  "profile40" => "ava40",
  "avatar_32" => "ava32",
  "avatar_profile" => "gender64",
  "avatar_big" => "genderreal64",
  "avatar_small" => "gender48",
  "community" => "community100",
  "community_big" => "community64",
  "community_48" => "community48",
  "community_32" => "community32",
  "shop_big" => "homa",
  "shop_small" => "homa32",
  "shop" => "homa"
);