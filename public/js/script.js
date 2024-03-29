﻿$(document).ready(function() {
  var $main = $('main');

  var timing = window.performance.timing,
      gen_time_html = ((timing.responseEnd - timing.connectStart) / 1000).toFixed(4);

  $('#debug span').html(gen_time_html);
  $('.preview-image').on('load', function() { lazyLoad($(this)); });
    
  $main.on('click', '.v-blind thead tr', function() { $(this).parents('table').toggleClass('down'); });
  $main.on('click', '.close-shadow-page', function() { $('.shadow-page').fadeOut(); });
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
 * Выбор даты в календаре
 * @param {*} dt_name 
 * @param {*} dt_value 
 * @param {*} el 
 */
function selectDateInCalendar(dt_name, dt_value, el) {
  $ds = $('.date-select.date-'+dt_name);
  $ds.find('td.data-value').html(dt_value);
  $ds.find('li').removeClass('current');
  $ds.find('input').val(dt_value);
  $(el).addClass('current');
  $('.shadow-page').fadeOut();
}

/**
 * Добавление поля для вставки переменной
 */
function addValueRow(item, row, obj) {
  var source   = $("#" + row).html(),
      template = Handlebars.compile(source),
      $placing = $("#" + item);

  html = template(obj);
  $placing.append(html);
  $placing.find('tr').each(function(i, elem) {
    $(elem).find('td:nth-child(1)').html(i+1+'.');
    if (i != 0) $(elem).find('td:nth-child(5)').html('<span>[R]</span>');
  });
}

/**
 * Отправка и получения запроса curl 
 * ---------------------------------
 */
function sendRequestCurl() {
  var $error = $('#error'),
      url_request = $('#url_request').val(),
      params;

  if (!url_request) { $error.show().html('Укажите адрес запроса'); return false; }

  params = { url_request: url_request };
  
  sendAjax(
    params, 
    "/api/send-request-curl/", 
    $('#success'), 
    $('#submit_button'), 
    $('#preloader'), 
    $('#media-container'), 
    $error
  );
}

/**
 * Построить расчет затрат
 * -----------------------
 */
function buildBudget() {
  var $error = $('#error'),
      dt_start = $('input[name=dt_start]').val(),
      dt_end = $('input[name=dt_end]').val();

  if (!dt_start) { $error.show().html('Укажите начало периода'); return false; }
  if (!dt_end) { $error.show().html('Укажите конец периода'); return false; }

  params = {
    dt_start: dt_start,
    dt_end: dt_end
  };

  sendAjax(
    params,               // Данные для отправки
    "/budget/build/",     // Url на который переаются данные
    $('#success'),        // 
    $('#submit_button'),  // Кнопка для блокировки
    $('#preloader'),      // Идентификатор блока загрузчика
    $('#build-budget'),   // Идентификатор блока в который будет выведен ответ
    $error                // Идентификатор блока в который будет выведена ошибка
  );
}

/**
 * Сохранение данных Бюджета
 * -------------------------
 */
function saveBudget() {
  var formdata = $('.budget-day').serialize(),
      hash = $('input[name="hash"]').val();

  $.ajax({
    url: "/budget/save/",
    type: "POST",
    data: formdata,
    success: function(data) {
      //console.log(data);
      
      $('.content').html(data);
      // Смена страницы
      window.history.pushState('', '', '/budget/show/'+hash+'/');
      
    },
    error: function (error) {
      console.log(error);
    }
  });
}

/**
 * Подгрузка элементов
 * -------------------
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
 * ----------------------------------------
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
 * Отправка AJAX-запроса
 * ---------------------
 * @param params - данные для отправки
 * @param url - адорес для передачи данных
 * @param success - блок который будет спрятан на время выполнения запроса
 * @param submit_button - кнопка для блокировки
 * @param preloader - блок выводится пока происходит ожидание ответа
 * @param media_container - блок в котором будет выведен ответ
 * @param error - блок в котором будут выведены ошибки
 */
function sendAjax(params, url, $success, $submit_button, $preloader, $media_container, $error) {
  $success.hide();
  $submit_button.attr('disabled', true);
  $preloader.fadeIn(function(){
    $.ajax({
      url: url,
      data: params,
      success: function(obj) {
        $error.hide();
        $media_container.html(obj);
        $success.show();
      },
      error: function (error) {
        console.log(error.responseJSON);
        var error = error.responseJSON.description;
        $error.show().html(error);
        $success.hide();
      },
      complete: function() {
        $preloader.hide();
        $submit_button.attr('disabled', false);
      }
    });
  });
}

/**
 * Копирует текст из переданного элемента (element)
 * ------------------------------------------------
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
 * --------------------
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
 * Удаляет медиа-файл из альбома пользователя
 * ------------------------------------------
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

/**
 * Обновления блока с датами месяца календаря
 * ------------------------------------------
 * @param {*} el 
 * @param {*} month 
 * @param {*} name 
 */
function update_calendar(el, month, name) {
  $.get("/blocks/blockSetDate/", { month: month, name: name })
  .done(function(data) { 
    $(el).parents('.shadow-page').html(data);
  });
}