<?php
// допустимые типы и размеры превьюшек
// например: "/data/cache/2009/1/1/123-48x48x.jpg"
$THUMB_SIZES = array(
  "item" => "600x600x",
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