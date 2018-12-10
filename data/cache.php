<?php
/*
 *  http://www.project.local/data/cache/2017mar/25/04/39888_94775-300x220x.jpg
 */





define('BASE_DIR', dirname(dirname(__FILE__)));
include BASE_DIR . '/config/thumbs.php';

// параметров в УРЛ не должно быть
if (is_int(strpos($_SERVER["REQUEST_URI"], '?'))) {
  header("HTTP/1.0 404 Not Found");
  exit;
}

// убираем известную часть пути
$filename = str_replace('/data/cache/', '', $_SERVER["REQUEST_URI"]);

// проверяем соответствие полному варианту записи адреса
if (!preg_match("/^(\d{4}\w{3})\/(\d{2})\/(\d{2})\/([\d_]+)-(\d+)x(\d+)([a-z]{0,1})\.(jpg|gif)$/", $filename, $matches)) {
  // проверяем соответствие варианту с thumb
  if (preg_match("/^(\d{4}\w{3})\/(\d{2})\/(\d{2})\/([\d_]+)(?:thumb|nothumb)(\d+)\.jpg$/", $filename, $matches) && in_array($matches[5], array(300, 500, 650))) {
    $matches[6] = "0";
    $matches[7] = "";
    $matches[8] = "jpg";
  } // проверяем соответствие сокращенному варианту - для вывода оригинального размера
  elseif (preg_match("/^(\d{4}\w{3})\/(\d{2})\/(\d{2})\/([\d_]+)\.jpg$/", $filename, $matches)) {
    $matches[5] = "original";
    $matches[6] = "original";
    $matches[7] = "";
    $matches[8] = "jpg";
  } else {
    header("HTTP/1.0 404 Not Found");
    exit;
  }
}

$dir = $matches[1] . '/' . $matches[2] . '/' . $matches[3];
$id = $matches[4];
$desiredW = $matches[5];
$desiredH = $matches[6];
$mod = $matches[7];
$ext = $matches[8];

// проверяем допустимость указанного размера
if ($desiredW != 'original') {
  $size = $desiredW . 'x' . $desiredH . $mod;
  if (!in_array($size, $GLOBALS['THUMB_SIZES'])) {
    header("HTTP/1.0 404 Not Found");
    exit;
  }
}

/*
mod:
f - frame - добавление полей для сохранения пропорций
c - cover - стопка фотографий
p - photo - заданный размер поворачивается на 90* для сохранения ориентации исходного кадра
x - krop  - кроп под указанный размер
m - map   - для карты
*/
$mod_frame = false;
$mod_cover = false;
$mod_photo = false;
$mod_krop = false;
$mod_map = false;
if ($mod == 'f') $mod_frame = true;
if ($mod == 'c') $mod_cover = true;
if ($mod == 'p') $mod_photo = true;
if ($mod == 'x') $mod_krop = true;
if ($mod == 'm') {
  $mod_krop = true;
  $mod_map = true;
}

$original_filename = BASE_DIR . '/data/originals/' . $dir . '/' . $id . '.jpg';
$cache_filename = BASE_DIR . '/data/cache/' . $filename;
$tmp_filename = $cache_filename . '.tmp';
$nomark_filename = str_replace('.jpg', '.nomark', $original_filename);

if (!file_exists($original_filename) || !($fsize = filesize($original_filename))) {
  header("HTTP/1.0 404 Not Found");
  exit;
}

$image_info = getimagesize($original_filename);
if (!$image_info || empty($image_info[0]) || empty($image_info[1])) {
  header("HTTP/1.0 404 Not Found");
  exit;
}

$oldW = $image_info[0];
$oldH = $image_info[1];

// создаем директорию в кеше, если вдруг она не создалась
$oldumask = umask(0);
if (!file_exists(BASE_DIR . '/data/cache/' . $dir)) mkdir(BASE_DIR . '/data/cache/' . $dir, 0775, true);
umask($oldumask);

// чтобы не перестраивать одновременно
for ($i = 0; $i < 10; ++$i) {
  if (!file_exists($tmp_filename)) break;

  usleep(200000);
}

if (!file_exists($tmp_filename)) {
  $fp = fopen($tmp_filename, "w");
  fwrite($fp, "1");
  fclose($fp);
}

if ($desiredW == 'original') {
  $desiredW = $oldW;
  $desiredH = $oldH;
}

// один из размеров - плавающий, другой - ограничение сверху
if ($desiredH == 0) {
  if ($desiredW >= $oldW) {
    $desiredW = $oldW;
    $desiredH = $oldH;
  } else {
    $desiredH = round($oldH*$desiredW/$oldW);
  }
}
if ($desiredW == 0) {
  if ($desiredH >= $oldH) {
    $desiredW = $oldW;
    $desiredH = $oldH;
  } else {
    $desiredW = round($oldW*$desiredH/$oldH);
  }
}

// заданы оба размера, но один из них больше, чем исходный (максимально допустимый)
if (!$mod_krop && ($desiredW > $oldW || $desiredH > $oldH)) {
  if ($desiredW > $oldW && $desiredH > $oldH) {
    $desiredW = $oldW;
    $desiredH = $oldH;
  } elseif ($desiredH > $oldH) {
    $desiredH = round($oldH*$desiredW/$oldW);
  } else {
    $desiredW = round($oldW*$desiredH/$oldH);
  }
}

// меняем ориентацию для фото и альбомов, кроме случаев плавающей ширины или высоты
if (($mod_cover || $mod_photo) && $desiredW && $desiredH) {
  // только если ориентация исходного кадра и запроса не совпадают
  if (($oldW > $oldH && $desiredH > $desiredW) || ($oldH > $oldW && $desiredW > $desiredH)) {
    $temp = $desiredH;
    $desiredH = $desiredW;
    $desiredW = $temp;
  }
}

try {
  $im = new Imagick($original_filename);
} catch (Exception $ex) {
  header("HTTP/1.0 404 Not Found");
  if (file_exists($tmp_filename)) unlink($tmp_filename);
  exit;
}

// подсчитаем количество кадров
$frames_count = 0;
foreach ($im as $im_frame) $frames_count++;

// анимационный gif, размеры которого не требуется уменьшать, не обрабатываем
if ($frames_count > 1 && $desiredW >= $oldW && $desiredH >= $oldH) {
  copy($original_filename, $cache_filename);
  header('Content-type: ' . $image_info['mime']);
  readfile($cache_filename);
  if (file_exists($tmp_filename)) unlink($tmp_filename);
  exit;
}

if ($frames_count > 1) $im = $im->coalesceImages();

if ($frames_count == 1 || $fsize > 50*1024 || $desiredH > 64 || $desiredW > 64) {
  // нет анимации или анимацию нужно убрать, т.к. gif слишком велик или не является смайлом
  if ($mod_frame) {
    $im->thumbnailImage($desiredW, $desiredH, true);
    $canvas = new Imagick();
    $canvas->newImage($desiredW, $desiredH, 'white', 'jpeg');
    $geometry = $im->getImageGeometry();
    $x = ($desiredW - $geometry['width'])/2;
    $y = ($desiredH - $geometry['height'])/2;
    $canvas->compositeImage($im, Imagick::COMPOSITE_OVER, $x, $y);
    $canvas->writeImage($cache_filename);
  } else {
    if ($mod_krop) {
      if ($oldH/$oldW > 1) {
        // картинка, вытянутая по вертикали - скорее всего лицо сверху, поэтому при обрезании оставляем верхнюю часть
        $im->cropImage($oldW, $oldW*($desiredH/$desiredW), 0, 0);
      }
      $im->cropThumbnailImage($desiredW, $desiredH);
    } else {
      $im->thumbnailImage($desiredW, $desiredH, true);
    }
    $geometry = $im->getImageGeometry();
    $canvas = new Imagick();
    $canvas->newImage($geometry['width'], $geometry['height'], 'white', 'jpeg');
    $canvas->compositeImage($im, Imagick::COMPOSITE_OVER, 0, 0);
    $canvas->writeImage($cache_filename);
  }
} elseif ($frames_count > 1) {
  // обработка анимированного gif
  $geometry = $im->getImageGeometry();

  // белый фон
  $canvas = new Imagick();
  $canvas->newImage($geometry['width'], $geometry['height'], 'white', 'gif');

  $i = 0;
  foreach ($im as $im_frame) {
    if ($mod_frame) {
      $im_frame->thumbnailImage($desiredW, $desiredH, true);
      $im_frame->setImagePage($desiredW, $desiredH, 0, 0);
    } else {
      if ($mod_krop) {
        $im->cropThumbnailImage($desiredW, $desiredH);
        $im_frame->setImagePage($desiredW, $desiredH, 0, 0);
      } else {
        $im_frame->thumbnailImage($desiredW, $desiredH, true);
      }
    }
    if ($i == 0) {
      $clone = $im_frame->clone();
      $im_frame->compositeImage($canvas, Imagick::COMPOSITE_OVER, 0, 0);
      $im_frame->compositeImage($clone, Imagick::COMPOSITE_OVER, 0, 0);
      $i++;
    }
  }
  $gif_cache_filename = str_replace('.jpg', '.gif', $cache_filename);
  $im->writeImages($gif_cache_filename, true);
  rename($gif_cache_filename, $cache_filename);
}

// делаем эффект стопки фотографий
if ($mod_cover) {
  $info = getimagesize($cache_filename);
  $w = $info[0];
  $h = $info[1];
  $w1 = $w - 20;
  $h1 = $h - 10;
  $w2 = $w + 80;
  $h2 = $h + 70;
  $b = intval($w/16);

  $rand1 = rand(3, 8);
  $rand2 = rand(-8, -2);
  $rand3 = rand(-8, 8);
  while ($rand3 == 0) $rand3 = rand(-5, 5);

  $canvas = new Imagick();
  $canvas->newImage($desiredW, $desiredH, 'white', 'png');
  $canvas->adaptiveResizeImage($desiredW + 30, $desiredH + 30);
  $canvas->setImageFormat("png");

  $image1 = new Imagick($cache_filename);
  $image1->setImageBackgroundColor(new ImagickPixel("gray"));
  $image2 = $image1->clone();
  $image3 = $image1->clone();

  $bg = new ImagickDraw();

  $image1->borderImage(new ImagickPixel("white"), $b, $b);
  $image1->borderImage(new ImagickPixel("gray60"), 1, 1);
  $image1->rotateImage(new ImagickPixel("transparent"), $rand1);
  $image1s = $image1->clone();
  $image1s->shadowImage(20, 2, 2, 2);
  $image1s->compositeImage($image1, Imagick::COMPOSITE_OVER, 0, 0);

  $image2->borderImage(new ImagickPixel("white"), $b, $b);
  $image2->borderImage(new ImagickPixel("gray60"), 1, 1);
  $image2->rotateImage(new ImagickPixel("transparent"), $rand2);
  $image2s = $image2->clone();
  $image2s->shadowImage(20, 1, 2, 2);
  $image2s->compositeImage($image2, Imagick::COMPOSITE_OVER, 0, 0);

  $image3->borderImage(new ImagickPixel("white"), $b, $b);
  $image3->borderImage(new ImagickPixel("gray60"), 1, 1);
  $image3->rotateImage(new ImagickPixel("transparent"), $rand3);
  $image3s = $image3->clone();
  $image3s->shadowImage(20, 1, 2, 2);
  $image3s->compositeImage($image3, Imagick::COMPOSITE_OVER, 0, 0);

  $canvas->compositeImage($image1s, Imagick::COMPOSITE_OVER, 0, 0);
  $canvas->compositeImage($image2s, Imagick::COMPOSITE_OVER, 0, 0);
  $canvas->compositeImage($image3s, Imagick::COMPOSITE_OVER, 0, 0);
  $image3->shadowImage(30, 5, 2, 2);

  $canvas->writeImage($cache_filename);
}

$info = getimagesize($cache_filename);
$add_watermark = ($info[0] >= 300 && $frames_count == 1 && !file_exists($nomark_filename));
if ($add_watermark) {
  $watermarkfile = '/public/img/watermark/w300.png';
  if ($info[0] > 500) $watermarkfile = '/public/img/watermark/w400.png';
  //if ($info[0] > 800) $watermarkfile = '/public/img/watermark/w800.png';

  $watermark = new Imagick(BASE_DIR . $watermarkfile);
  $image = new Imagick($cache_filename);
  $width = $image->getImageWidth() - $watermark->getImageWidth();
  $height = $image->getImageHeight() - $watermark->getImageHeight();
  $image->compositeImage($watermark, $watermark->getImageCompose(), $width, $height, Imagick::COLOR_ALPHA);
  $image->writeImage($cache_filename);
}

// для карты
if ($desiredW == 44 && $desiredH == 44) {
  $mapframe = new Imagick(BASE_DIR . '/i/map/frame.gif');
  $icon = new Imagick($cache_filename);
  $mapframe->compositeImage($icon, $icon->getImageCompose(), 3, 3, Imagick::COLOR_ALPHA);
  $mapframe->writeImage($cache_filename);
}
if ($desiredW == 16 && $desiredH == 16 && $mod_map) {
  $mapframe = new Imagick(BASE_DIR . '/i/map/frame-small.gif');
  $icon = new Imagick($cache_filename);
  $mapframe->compositeImage($icon, $icon->getImageCompose(), 2, 2, Imagick::COLOR_ALPHA);
  $mapframe->writeImage($cache_filename);
}

header('Content-type: ' . $image_info['mime']);
readfile($cache_filename);
if (file_exists($tmp_filename)) unlink($tmp_filename);

exit;