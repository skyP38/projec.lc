<?php

  $get = (!empty($_GET) && isset($_GET['route']))
  ? explode('/', $_GET['route'])
  : false;

  $path = match(true) {

    //homepage
    (!is_array($get)) => DIR_VIEW . 'landing.php',

    // account
    ($get[0] === 'account' && !isset($get[1])) => DIR_VIEW . 'account.php',
    (($get[0] === 'account') && isset($get[1]) && ($get[1] === 'account-reset')) => DIR_VIEW . 'reset.php',

    ($get[0] === 'reg') => DIR_VIEW . 'reg.php',
    ($get[0] === 'login') => DIR_VIEW . 'home.php',

    // api
    ($get[0] === 'api') => match(true) {
      (!isset($get[1])) => DIR_VIEW . '404.php',

      ($get[1] === 'auth') => DIR_API . 'auth.php', // попытка авторизации

      ($get[1] === 'get-data') => DIR_API . 'getData.php', // получить данные

      ($get[1] === 'set-data') => DIR_API . 'setData.php', // записать данные

      ($get[1] === 'account-exit') => DIR_API . 'exit.php', // выход из лк

      ($get[1] === 'reg') => DIR_API . 'reg.php', // попытка регистрации

      ($get[1] === 'new-pwd') => DIR_API . 'new.php', // заполнение формы с паролем
      ($get[1] === 'reset') => DIR_API . 'reset.php',
      ($get[1] === 'open-card') => DIR_API . 'open_card.php',
      true => DIR_VIEW . '404.php', // 404
    },

    // 404
    true => DIR_VIEW . '404.php',
  };
?>
