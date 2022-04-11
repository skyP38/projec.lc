<?php

  use Models\Crypt as Crypt;
  $crypt = new Crypt(
    require DIR_MODELS . 'crypt' . DSE . 'register.php',
    require DIR_MODELS . 'crypt' . DSE . 'matrix.php',
  );

  $auth_status = false;

  $sess_status = session_start();
  $sessid = session_id();

  $atoken = (!empty($_COOKIE['atoken']))
  ? $_COOKIE['atoken']
  : false;

  $dtoken = (!empty($_COOKIE['dtoken']))
  ? $_COOKIE['dtoken']
  : false;

  if (($dtoken != false) && ($atoken != false)) {
    $user = $db->users->findOne([
      'sesstoken' => $sessid,
      'atoken' => $atoken,
      'dtoken' => $dtoken,
    ]);


    if ( (!empty($user)) && ($user != NULL) ) {
      $auth_status = true;
      if(is_bool($get) || $get[0] != 'account') {
        header('Location: /account');
      }
    }elseif (!is_bool($get) && ($get[0] === 'account') && ($get[1] != 'account-reset')) {
      header('Location: /');
    }
  }elseif (!is_bool($get) && ($get[0] === 'account') && ($get[1] != 'account-reset')) {
    header('Location: /');
  }



  function createToken()
  {
    $abc = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+=-,.<>/?[]{}|';
    $len = strlen($abc) - 1;
    $token = '';
    for ($i=0; $i < 128; $i++) {
      $token .= $abc[rand(0, $len)];
    }
    return sha1(md5($token));
  }
?>
