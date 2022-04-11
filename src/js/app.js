$(document).ready(function () {
  $('form.locale').submit(function () {
    $.ajax({
      type: "POST",
      data: $( this ).serialize(),
      url: '/api/auth',
      beforeSend: function () {
        $('form.locale>button').html('Load form');
      },
      success: function(e){
        $('.result').html(e);
        $('form.locale>button').html('Update');
      }
    });
  });

  // let data = [0,2,4,8,16];
  //
  // $('input[name="pwd_title"]').val(data[0]);

  // $('.open-popup').click(function(e){
  //   e.preventDefault();
  //   //появление окна?
  //   $('.popup-bg').fadeIn(800);
  //   $('html').addClass('no-scroll');
  // });
  // $('.close-popup').click(function() {
  //   //исчезновение окна
  //   $('.popup-bg').fadeOut(800);
  //   $('html').removeClass('no-scroll');
  // });

  $('form.reg').submit(function () {
    $.ajax({
      type: "POST",
      data: $( this ).serialize(),
      url: '/api/reg',
      beforeSend: function () {
        $('form.reg>button').html('Load form');
      },
      success: function(e){
        $('.result').html(e);
        $('form.reg>button').html('Update');
      }
    });
  });

  $('form.reset').submit(function () {
    $.ajax({
      type: "POST",
      data: $( this ).serialize(),
      url: '/api/reset',
      beforeSend: function () {
        $('form.reg>button').html('Load form');
      },
      success: function(e){
        $('.result').html(e);
      }
    });
  });

  // $('a#registration').click(function () {
  //   $.ajax({
  //     type: "GET",
  //     data: false,
  //     url: '/api/reg',
  //     success: function(e){
  //       // window.location = "/";
  //       console.log('reg');
  //     }
  //   });
  // });

  let info_status = false;
  $('button#info_pwd').click(function (){
    (info_status)
    ? console.log('also open')
    : console.log('open info about pwd');
    info_status=!info_status;
  });


  $('a#account_exit').click(function () {
    $.ajax({
      type: "GET",
      data: false,
      url: '/api/account-exit',
      success: function(e){
        window.location = "/";
      }
    });
  });


  $('a#get_pwd').click(function () {
    let self = this;
    $.ajax({
      type: "POST",
      data: {
        'id' : $(self).attr('data-pwd-id')
      },
      url: '/api/open-card',
      success: function(e){
          let data = JSON.parse(e);
          $('input[name="pwd_title"]').val(data.title);
          $('input[name="val_login"]').val(data.login);
          $('input[name="val_pwd"]').val(data.pwd);
          $('.save_new_pwd button[name="clean"]').css({
              'display': 'block',
          });
      }
    });
  });

  $(this).scroll(function (e){
    $('.save_new_pwd').css({
      'transform' : `translateY(${$(this).scrollTop()}px)`
    });
  });

  $('a#clean').click(function () {
    $('input[name="pwd_title"]').val('');
    $('input[name="val_login"]').val('');
    $('input[name="val_pwd"]').val('');
    $(this).remove();
  });


  $('form.save_new_pwd').submit(function() {
    $.ajax({
      type: "POST",
      data: $( this ).serialize(),
      url: '/api/new-pwd',
      beforeSend: function() {
        $('form.save_new_pwd>button').html('Load form');
      },
      success: function(e) {
        console.log('new pwd');
        $('form.save_new_pwd>button').html('Save');
        $('input[name="pwd_title"]').val('');
        $('input[name="val_login"]').val('');
        $('input[name="val_pwd"]').val('');
        window.location = "/";
      }
     });
  });

  let pwd_status = false;

  $('a#view_pwd').click(function () {
    (pwd_status)
    ? $('input#pwd_data').attr('type', 'password')
    : $('input#pwd_data').attr('type', 'text');

    pwd_status = !pwd_status;
  });
});


function q() {
}
