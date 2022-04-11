<?php
use Models\Crypt as Crypt;
$crypt = new Crypt(
  require DIR_MODELS . 'crypt' . DSE . 'register.php',
  require DIR_MODELS . 'crypt' . DSE . 'matrix.php',
);
if ($_SERVER['REQUEST_METHOD'] ==='POST') {

  $users = $db->users;

  $res = $users->findOne(['type' => 'count']);
  $count = $res->count;

  $count++;

  $validate = true;

  $doc = array(
    'uid'         => $count,
    'type'        => 'user',
    'uname'       => (isset($_POST['uname'])      && !empty($_POST['uname']))     ? $_POST['uname'] : false,
    'pwd'         => (isset($_POST['pwd'])        && !empty($_POST['pwd']))       ? $_POST['pwd'] : false,
    'pwd2'        => (isset($_POST['pwd2'])       && !empty($_POST['pwd2']))      ? $_POST['pwd2'] : false,
    'email'       => (isset($_POST['email'])      && !empty($_POST['email']))     ? $_POST['email'] : false,
    'phone'       => (isset($_POST['phone'])      && !empty($_POST['phone']))     ? $_POST['phone'] : false,
    'first_name'  => (isset($_POST['first_name']) && !empty($_POST['first_name']))? $_POST['first_name'] : false,
    'last_name'   => (isset($_POST['last_name'])  && !empty($_POST['last_name'])) ? $_POST['last_name'] : false,
  );

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

  if ($validate) {

    $abc_cl = [
      'a' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
      'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k',
      'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
      'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
      'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => '', 'ь' => '',
      'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    ];

    $nums = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

    $ru = ['a', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к',
    'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц',
    'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'];

    $en = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
    'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

    $doc['uname'] = strtolower($doc['uname']);
    $doc['uname'] = str_replace(' ', '', $doc['uname']);
    $doc['first_name'] = strtolower($doc['first_name']);
    $doc['last_name'] = strtolower($doc['last_name']);

    for ($i=0; $i < strlen($doc['uname']); $i++) {
      if ( (in_array($doc['uname'][$i], $en)) || (in_array($doc['uname'][$i], $ru)) ) {
        foreach ($abc_cl as $ru_val => $en_val) {
          $doc['uname'][$i] = str_replace($ru_val, $en_val, $doc['uname'][$i]);
        }
      }else {
        echo "Имя пользователя содержит недопустимые символы!";
        exit();
      }
    }



    $validate = (($doc['pwd'] === $doc['pwd2']) && (sha1($doc['pwd']) === sha1($doc['pwd2'])));

    if (!$validate) {
      echo "Пароли не совпадают!";
      exit();
    }

    $doc['phone'] = str_replace(' ','', $doc['phone']);

    for ($i=0; $i < strlen($doc['phone']); $i++) {
      $doc['phone'][$i] = (in_array($doc['phone'][$i], $nums)) ? $doc['phone'][$i] : str_replace('', $doc['phone'][$i]);
    }


    if (strlen($doc['phone']) === 11) {
      $doc['phone'][0] = ($doc['phone'][0] === '8')
      ? '7'
      : '7';
      $doc['phone'] = '+' . $doc['phone'];
    }elseif (strlen($doc['phone']) === 10) {
      $doc['phone'] = '+7' . $doc['phone'];
    }else {
      echo "Неверный формат номера!";
      exit();
    }

    $status = in_array($doc['first_name'][0], $ru) ? true : false;

    for ($i=1; $i < strlen($doc['first_name']); $i++) {
      if($status != in_array($doc['first_name'][$i], $ru)){
        echo "Фамилия и имя должны быть написаны на русском языке.";
        exit();
      }
    }

    $status = in_array($doc['last_name'][0], $ru) ? true : false;

    for ($i=1; $i < strlen($doc['last_name']); $i++) {
      if($status != in_array($doc['last_name'][$i], $ru)){
        echo "Фамилия и имя должны быть написаны на русском языке.";
        exit();
      }
    }

    $doc['first_name'] = ucfirst($doc['first_name']);
    $doc['last_name'] = ucfirst($doc['last_name']);


    $res1 = $users->findOne([
      'uname' => $doc['uname']
    ]);

    $res2 = $users->findOne([
      'phone' => $doc['phone']
    ]);

    $res3 = $users->findOne([
      'email' => $doc['email']
    ]);

    $validate = ( (is_null($res1) || ($res1 === NULL)) && (is_null($res2) || ($res2 === NULL)) && (is_null($res3) || ($res3 === NULL)) )
    ? true : false;


    if ($validate) {
      unset($doc['pwd2']);
      $doc['pwd'] = sha1($doc['pwd']);
      $doc['pwd_crypt'] = $crypt->encode($doc['pwd']);
      $users->insertOne($doc);
      $users->updateOne(
        ['type' => 'count'],
        ['$set' => ['count' => $count]]
      );

      echo "Регистрация успешно завершена!";
      echo "<script> setInterval(function(){window.location = '/login';}, 2000);</script>";
    }else {
      echo match(true) {
        (!is_null($res1) || ($res1 != NULL)) => 'Пользователь с таким никнемом уже существует!',
        (!is_null($res2) || ($res2 != NULL)) => 'Пользователь с таким номером телефона уже существует!',
        (!is_null($res3) || ($res3 != NULL)) => 'Пользователь с таким e-mail уже существует!',
        true => 'Непредвиденная ошибка валидации данных! Обратитесь в службу поддержки.',
      };
      exit();
    }
  }else {
    echo "Непредвиденная ошибка, проверьте корректность введенных данных";
    exit();
  }
}
 ?>
