$(document).ready(function(){
  var timing = window.performance.timing,
      gen_time_html = ((timing.responseEnd - timing.connectStart) / 1000).toFixed(4);

  $('#debug span').html(gen_time_html);
});

$(window).load(function () { lazyLoad(); });
$(window).on("scroll", function() {   });
$(window).on("resize", function() {   });

/**
 * Подгрузка элементов
 * @param url
 * @param element
 */
function uploadMoreItems(url, element) {
  var $preloader = $('#preloader');

  $preloader.show();
  $(element).hide();
  $.ajax({
    url: url,
    success: function(data) {
      var obj = $.parseJSON(data);
      if (obj.complete) {
        $preloader.hide();
        $(element).replaceWith(obj.complete);
        lazyLoad();

        url = url.replace(/[&?]mode=upload/g, "");
        window.history.pushState('', '', url);
      } else {

      }
    },
    error: function (error) {
      console.log(error);
    }
  });
}

/**
 * Фоновая загрузка изображений тега img.preview-image
 */
function lazyLoad() {
  var $images = $('.preview-image');
  $images.each(function(){
    var $img = $(this),
        src = $img.attr('data-desktop');

    $img.on('load', function(){ $(this).attr('class', 'loaded') }).attr('src', src);
  });
}

/**
 * Подгружает контент полученый с Instagram
 * @returns {boolean}
 */
function uploadMediaInsta() {
  var source   = $("#media-template").html(),
      template = Handlebars.compile(source),
      media_page_url = $('#instagram_media_page_url').val(),
      $submit_button = $('#submit_button'),
      $preloader = $('#preloader'),
      $success = $('#success');

  if (!media_page_url) {
    $('#error').show().html('Укажите ссылку на пост в Instagram');
    return false;
  }

  $success.hide();
  $submit_button.hide();
  $preloader.fadeIn(function(){
    $.ajax({
      url: "/api/media/",
      data: {
        instagramMediaPageUrl: media_page_url
      },
      success: function(obj) {
        // var obj = $.parseJSON(data);
        var html = template(obj.info);

        $('#error').hide();
        $('#media-container').html(html);

        $preloader.hide();
        $submit_button.show();
        $success.show();
      },
      error: function (error) {
        console.log(error.responseJSON);
        var error = error.responseJSON.description;

        $('#error').show().html(error);

        $preloader.hide();
        $submit_button.show();
        $success.hide();
      }
    });
  });
}

/**
 * Вставляет текст из буфера обмена
 * @param id
 */
function pasteClipboard(id) {

}

/**
 * Копирует текст из переданного элемента (element)
 * @param element
 */
function copyToClipboard(element) {
  var $temp = $("<textarea>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

/**
 * Сохраняет медиа файл
 * @param type
 * @param url
 * @param el
 */
function saveMedia(type, url, el) {
  var $el = $(el);
  $el.removeAttr('onclick').addClass('in-progress').html('Идет сохранение...');

  $.ajax({
    url: "/ajax/saveinstamedia/",
    data: {
      type: type,
      url: url
    },
    success: function(data) {
      var obj = $.parseJSON(data);
      if (obj.complete) {
        $el.replaceWith('<span>Сохранено</span>');
      } else {
        $el.replaceWith('<span>Ошибка, попробуйте скачать медиа файл</span>');
      }
    },
    error: function (error) {
      console.log(error);
    }
  });
}

/**
 * Удаляет медиа файл из альбома пользователя
 * @param id
 * @param el
 */
function removeMedia(id, el) {
  $.ajax({
    url: "/ajax/removeinstamedia/",
    data: {
      id: id
    },
    success: function(data) {
      var obj = $.parseJSON(data);

      if (obj.complete) {
        $('#media-' + id).slideUp(function() {
          $(this).remove();
        });
      }
    },
    error: function(error) {
      console.log(error);
    }
  });
}