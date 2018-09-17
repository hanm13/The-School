function readURL(input,imageClass) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $(imageClass).attr('src', e.target.result);
      $('#image_hidden_input').val(e.target.result);
    }

    reader.readAsDataURL(input.files[0]);

  }
}

$( document ).ready(function() {

  $(".file_upload").change(function() {
    readURL(this, ".imagePreview");
    $('.imagePreview').css("display","inline");

  });

  $('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    // other options
  });

});
