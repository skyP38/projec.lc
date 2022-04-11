<?php
use Models\Crypt as Crypt;
$crypt = new Crypt(
  require DIR_MODELS . 'crypt' . DSE . 'register.php',
  require DIR_MODELS . 'crypt' . DSE . 'matrix.php',
);
if($_SERVER['REQUEST_METHOD'] === 'POST') {

  $doc = [
    'pwd1' => (isset($_POST['pwd1']) && !empty($_POST['pwd1'])) ? $_POST['pwd1'] : false,
    'pwd2' => (isset($_POST['pwd2']) && !empty($_POST['pwd2'])) ? $_POST['pwd2'] : false,
  ];

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
  $validate = ($doc['pwd1'] === $doc['pwd2']) ? $validate : false;

  if($validate){
    $udata = [
      'sessid' => session_id(),
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
    $user_card = $db->users->findOne([
      'type' => 'user',
      'sesstoken' => $udata['sessid'],
      'atoken' => $udata['atoken'],
      'dtoken' => $udata['dtoken'],
    ]);

    $db->users->updateOne(
      ['uid' => $user_card->uid],
      ['$set' => ['pwd' => $crypt->encode($doc['pwd1'])]]
    );
  } else{
    echo '!!!!Непредвиденная ошибка валидации данных! Обратитесь в службу поддержки.';
  }

}

 ?>
