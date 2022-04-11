<?php
use Models\Crypt as Crypt;
$crypt = new Crypt(
  require DIR_MODELS . 'crypt' . DSE . 'register.php',
  require DIR_MODELS . 'crypt' . DSE . 'matrix.php',
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $doc = array(
    'title' =>  (isset($_POST['pwd_title']) && !empty($_POST['pwd_title'])) ? $_POST['pwd_title'] : false,
    'pwd' =>  (isset($_POST['val_pwd']) && !empty($_POST['val_pwd'])) ? $_POST['val_pwd'] : false,
    'login' =>  (isset($_POST['val_login']) && !empty($_POST['val_login'])) ? $_POST['val_login'] : false,
  );

  $validate = true;
  foreach ($doc as $id => $val) {
    $val = strip_tags($val);
    $val = htmlentities($val, ENT_QUOTES, "UTF-8");
    $val = htmlspecialchars($val, ENT_QUOTES);
    $val = (!empty($val)) ? $val : false;
    $doc[$id] = $val;
    if (is_bool($val)) {
      $validate = false;
    }
  }

  // $validate = true;
  if($validate){

    $title = $doc['title'];
    $pwd = $doc['pwd'];
    $login = $doc['login'];

    // Поиск пользователя
    $udata = [
      'type' => 'user',
      'sesstoken' => session_id(),
      'atoken' => $_COOKIE['atoken'],
      'dtoken' => $_COOKIE['dtoken'],
    ];

    foreach ($udata as $id => $val) {
      $val = strip_tags($val);
      $val = htmlentities($val, ENT_QUOTES, "UTF-8");
      $val = htmlspecialchars($val, ENT_QUOTES);
      $val = (!empty($val)) ? $val : false;
      $udata[$id] = $val;
    }

    $user_card = $db->users->findOne($udata);

    $count = $db->keys->findOne(['type' => 'count']);
    $count = $count->count;
    $count++;

    $data  = [
      'type' => 'key',
      'id'  => $count,
      'uid'  => $user_card->uid,
      'title' => $crypt->encode($title),
      'login' => $crypt->encode($login),
      'pwd'   => $crypt->encode($pwd),
    ];

    $db->keys->insertOne($data);
    $db->keys->updateOne(
      ['type' => 'count'],
      ['$set' => ['count' => $count]]
    );
  }
  else{
    var_dump($doc);
    echo '!!!!Непредвиденная ошибка валидации данных! Обратитесь в службу поддержки.';
  }
}else {
  exit('Неверный вид запроса!');
}

?>
