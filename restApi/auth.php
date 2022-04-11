<?php
  use Models\Crypt as Crypt;
  $crypt = new Crypt(
    require DIR_MODELS . 'crypt' . DSE . 'register.php',
    require DIR_MODELS . 'crypt' . DSE . 'matrix.php',
  );

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sess_status = session_start();

    $doc = [
      'uname' => (isset($_POST['uname']) && !empty($_POST['uname'])) ? $_POST['uname'] : false,
      'email' => (isset($_POST['email']) && !empty($_POST['email'])) ? $_POST['email'] : false,
      'pwd' =>   (isset($_POST['pwd']) && !empty($_POST['pwd'])) ? $_POST['pwd'] : false,
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

    // if($validate){
        $uname =  $doc['uname'];
        $email =  $doc['email'];
        $pwd   =  $doc['pwd'];


        $data  = $db->users->findOne([
          'uname' => strtolower($uname),
          'email' => $email,
          'pwd'   => sha1($pwd),
        ]);



        if (!empty($data) && ($data != NULL)) {
          $atoken = createToken();
          $dtoken = createToken();
          $sessid = session_id();
          $db->users->updateOne(
            ['uname' => $uname],
            ['$set' => [
              'atoken'    => $atoken,
              'dtoken'    => $dtoken,
              'sesstoken' => $sessid,
            ]],
          );
          setcookie('atoken', $atoken, time()+60*60*24*30, '/');
          setcookie('dtoken', $dtoken, time()+60*60*24*30, '/');
          echo '
          <script>
            window.location = "/account";
          </script>
          ';
        }else {
          echo "Неверный логин или пароль!";
        }
      }else {
        exit('Access denied!');
      }
    // }
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
