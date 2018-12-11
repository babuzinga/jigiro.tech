$().ready(function(){
  $(window).load(function () {

  });





  $(document).ready(function () {
    var source   = $("#media-template").html();
    var template = Handlebars.compile(source);

    $('#submit_button').on("click", function(event) {
      var media_page_url = $('#instagram_media_page_url').val(),
          $preloader = $('#preloader');
      if (!media_page_url) {
        $('#error').show().html('Укажите ссылку на пост в Instagram');
        return false;
      }

      $(this).hide();
      $preloader.show();

      $.ajax({
        url: "/api/media",
        data: {
          instagramMediaPageUrl: media_page_url
        },
        success: function(obj) {
          // var obj = $.parseJSON(data);

          console.log(obj);
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

      $(this).show();
      $preloader.hide();
    });
  });





  $(window).on("scroll", function() {

  });





  $(window).on("resize", function() {

  });
});

function copyToClipboard(element) {
  var $temp = $("<textarea>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

function saveMedia(type, url) {

}