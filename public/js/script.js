$(document).ready(function() {
  var timing = window.performance.timing,
      gen_time_html = ((timing.responseEnd - timing.connectStart) / 1000).toFixed(4);

  $('#debug span').html(gen_time_html);
  $('.preview-image').on('load', function() { lazyLoad($(this)); });
  $('.close-shadow-page').click(function() { $('.shadow-page').fadeOut(); });

  var $date_select,
      $td_value = $('.date-select').find('td.value'),
      $li_dt = $('.date-select').find('li'),
      dt_value = 0,
      dt_name = 0;

  $td_value.click(function(){ 
    dt_name = $(this).data('name');
    $('.date-select.date-'+dt_name).find('.shadow-page').fadeIn(); 
  });
  $li_dt.click(function() {
    dt_value = $(this).data('value');
    dt_name = $(this).data('name');
    $date_select = $('.date-select.date-'+dt_name);
    $date_select.find('td.value').html(dt_value);
    $date_select.find('li').removeClass('current');
    $date_select.find('input').val(dt_value);
    $(this).addClass('current');
    $('.shadow-page').fadeOut();
  });
});

$(window).load(function () { lazyLoad(); });

var scroll_top, doc_height;
$(window).on("scroll", function() {
  // Пользователь долистал до низа страницы
  scroll_top =  $(window).scrollTop();
  doc_height = $(document).height() - $(window).height();
  //console.log(scroll_top + ' ' + doc_height);
  if  (scroll_top >= (doc_height - 200)) {
    if ($('#upload-item').length) uploadMoreItems('#upload-item');
  }
});

$(window).on("resize", function() {   });

/**
 * Добавление поля для вставки перемнной
 */
function addVariableRow() {
  var source   = $("#variable-row-template").html(),
      template = Handlebars.compile(source),
      $variable_table = $("#variable-table tbody");

  $variable_table.append(template);
  $variable_table.find('tr').each(function(i, elem) {
    $(elem).find('td:nth-child(1)').html(i+1+'.');
    if (i != 0) $(elem).find('td:nth-child(5)').html('[R]');
  });
}

/**
 * Отправка и получения запроса curl 
 */
function sendRequestCurl() {
  var url_request = $('#url_request').val(),
      $submit_button = $('#submit_button'),
      $preloader = $('#preloader'),
      $success = $('#success');

  if (!url_request) {
    $('#error').show().html('Укажите адрес запроса');
    return false;
  }

  $success.hide();
  $submit_button.hide();
  $preloader.fadeIn(function(){
    $.ajax({
      url: "/api/send-request-curl/",
      data: {
        url_request: url_request
      },
      success: function(obj) {
        $('#error').hide();
        $('#media-container').html(obj);

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
 Построить расчет затрат
 */
function buildCalculation() {
  var amount_money = $('#amount_money').val(),
      dt_start = $('input[name=dt_start]').val(),
      dt_end = $('input[name=dt_end]').val(),
      $submit_button = $('#submit_button'),
      $preloader = $('#preloader'),
      $success = $('#success');

  if (!amount_money) {
    $('#error').show().html('Укажите сумму');
    return false;
  }

  if (!dt_start) {
    $('#error').show().html('Укажите начало периода');
    return false;
  }

  if (!dt_end) {
    $('#error').show().html('Укажите конец периода');
    return false;
  }

  $success.hide();
  $submit_button.hide();
  $preloader.fadeIn(function(){
    $.ajax({
      url: "/ajax/buildcalculation/",
      data: {
        amount_money: amount_money,
        dt_start: dt_start,
        dt_end: dt_end
      },
      success: function(obj) {
        $('#error').hide();
        $('#media-container').html(obj);

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
 * Подгрузка элементов
 * @param url
 * @param element
 */
function uploadMoreItems(element) {
  var $button = $(element),
      $preloader = $('#preloader'),
      url = $button.data('url');

  $preloader.show();
  $button.hide();
  $.ajax({
    url: url,
    success: function(data) {
      var obj = $.parseJSON(data);
      if (obj.complete) {
        $preloader.hide();
        $button.replaceWith(obj.complete);
        lazyLoad();
        /*
        // Смена страницы
        url = url.replace(/[&?]mode=upload/g, "");
        window.history.pushState('', '', url);
        */
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
        src = $img.attr('data-src');

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

function sendAjax() {
  
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