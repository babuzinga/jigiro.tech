﻿$().ready(function(){
  $(window).load(function () {   });
  $(document).ready(function () {   });
  $(window).on("scroll", function() {   });
  $(window).on("resize", function() {   });
});



function uploadMediaInsta() {
  var source   = $("#media-template").html(),
      template = Handlebars.compile(source),
      media_page_url = $('#instagram_media_page_url').val(),
      $submit_button = $('#submit_button'),
      $preloader = $('#preloader');

  if (!media_page_url) {
    $('#error').show().html('Укажите ссылку на пост в Instagram');
    return false;
  }

  $submit_button.hide();
  $preloader.show();

  $.ajax({
    url: "/api/media",
    data: {
      instagramMediaPageUrl: media_page_url
    },
    success: function(obj) {
      // var obj = $.parseJSON(data);
      var html = template(obj.info);

      $('#error').hide();
      $('#media-container').html(html);
      $('#success').show();
    },
    error: function (error) {
      console.log(error.responseJSON);
      var error = error.responseJSON.description;

      $('#error').show().html(error);
      $('#success').hide();
    }
  });

  $submit_button.show();
  $preloader.hide();
}

function copyToClipboard(element) {
  var $temp = $("<textarea>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

function saveMedia(type, url, el) {

  $.ajax({
    url: "/ajax/saveinstamedia",
    data: {
      type: type,
      url: url
    },
    success: function(obj) {
      console.log(obj);
      $(el).replaceWith('<span>Сохранено</span>');
    },
    error: function (error) {
      console.log(error);
    }
  });
}