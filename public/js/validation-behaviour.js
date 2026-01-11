$(function(){
  $('.is-invalid').keyup(function(){
    if($(this).hasClass('is-invalid')){
      $(this).removeClass('is-invalid');
    }
  });
  $('.is-invalid').change(function(){
    if($(this).hasClass('is-invalid')){
      $(this).removeClass('is-invalid');
    }
    let name = $(this).attr('name');
    $('input[name="'+name+'"]').each(function () {
      $(this).removeClass('is-invalid');
    });

  });
  $('.is-invalid').on("changed.bs.select", function(){
    if($(this).hasClass('is-invalid')){
      $(this).removeClass('is-invalid');
    }
  });
});
