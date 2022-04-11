<?php

  define('DSE', DIRECTORY_SEPARATOR);

  define('DIR_ROOT', __DIR__ . DSE);

  define('DIR_API', DIR_ROOT . 'restApi' . DSE);

  define('DIR_CONTROLLERS', DIR_ROOT . 'controllers' . DSE);

  define('DIR_MODELS', DIR_ROOT . 'models' . DSE);

  define('DIR_VENDOR', DIR_MODELS . 'vendor' . DSE);

  define('DIR_VIEW', DIR_ROOT . 'view' . DSE);


  require_once DIR_VENDOR . 'autoload.php';
  require_once DIR_CONTROLLERS . 'router.php';
  require_once DIR_MODELS .'crypt.php';

  $__login = '********';
  $__pwd = '********';
  $__ip = '********';
  $__port = '********';

  $db = new \MongoDB\Client(
    "mongodb://$__login:$__pwd@$__ip:$__port"
  );

  $db = $db->crypto;

  if (is_bool($get) || ($get[0] != 'api')) {
    require_once DIR_MODELS . 'auth.php';
    require_once DIR_VIEW . 'default' . DSE . 'head.php';
    require_once DIR_VIEW . 'default' . DSE . 'nav.php';
    require_once $path;
    require_once DIR_VIEW . 'default' . DSE . 'footer.php';
  }else {
    session_start();
    require_once $path;
  }
/*
 * алгоритм дешифровки+ добавить заглавные буквы, цифры + матрицы дописать
 * визуал
 * на каком сайте можно иконки взять на страницу?
 * можно в роли наставника вас записать7
 * подключение sass

 * приемник морзе
 * начать с подписи и сохранения на сервере(попрробовать нейро)
 * canvas(method draw)-> js(save img)-> server->php(imagic?imagini?)->return info about user in bd
 * при помощи  веткоро впривдение к общему типу(либо через координаты)
 * глянуть отпечаток пальца для авторизации+каскады хаара
 * дополнительная идентификация пользователя через touch(%>90)
 */
?>
