$().ready(function(){
  $(window).load(function () {

  });





  $(document).ready(function () {
    var source   = $("#media-template").html();
    var template = Handlebars.compile(source);

    $('#submit_button').on("click", function(event) {
      var media_page_url = $('#instagram_media_page_url').val()
      $.ajax({
        url: "/api/media",
        data: {
          instagramMediaPageUrl: media_page_url
        },
        success: function(obj) {
          console.log(obj.info);
          var html = template(obj.info);

          $('#error').hide();
          $('#media-container').html(html);
          $('#success').show();
        },
        error: function (ajaxContext) {
          $('#error').show();
          $('#success').hide();
        }
      });
    });
  });





  $(window).on("scroll", function() {

  });





  $(window).on("resize", function() {

  });
});