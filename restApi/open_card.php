<?php
use Models\Crypt as Crypt;
$crypt = new Crypt(
  require DIR_MODELS . 'crypt' . DSE . 'register.php',
  require DIR_MODELS . 'crypt' . DSE . 'matrix.php',
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $doc = $_POST['id'];

  $validate = true;
    $doc = strip_tags($doc);
    $doc = htmlentities($doc, ENT_QUOTES, "UTF-8");
    $doc = htmlspecialchars($doc, ENT_QUOTES);
    $doc = (!empty($doc)) ? $doc : false;
    if (is_bool($doc)) {
      $validate = false;
    }

  if($validate){

    $data = $db->keys->findOne(['id' => (int) $doc]);

    $result = (object) [
      'title' => $crypt->decode($data->title),
      'login' => $crypt->decode($data->login),
      'pwd'   => $crypt->decode($data->pwd),
    ];
    echo json_encode($result);
  } else {
    echo "Непредвиденная ошибка, проверьте корректность введенных данных";
    exit();
  }




}

 ?>
