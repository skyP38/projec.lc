<div class="nav">
    <a href="/account/account-reset" id="account_reset">Обновить пароль</a>
    <div class="user_data">
      <!-- <h5>ID: <?php echo $user->uid ?></h5> -->
      <h5>Имя пользователя: <?php echo $user->uname ?></h5>
      <h5>E-mail: <?php echo $user->email ?></h5>
    </div>
    <a href="javascript: q();" id="account_exit">Выйти</a>
</div>

<?php
use Models\Crypt as Crypt;
$crypt = new Crypt(
  require DIR_MODELS . 'crypt' . DSE . 'register.php',
  require DIR_MODELS . 'crypt' . DSE . 'matrix.php',
);


  $passwords = $db->keys->find([
    'uid' => $user->uid
  ]);

  $data = [];
  $i = 0;
  foreach ($passwords as $doc) {
    $data[$i] = [];
    foreach ($doc as $field => $val) {
      $data[$i][$field] = $val;
    }
    $i++;
  }
?>

<div class="data_wrapper">
  <div class="pwd_wrapper">
  <?php if (count($data) === 0): ?>
    Данные не обнаружены...
    <?php else: ?>
      <?php foreach ($data as $card): ?>
        <div class="pwd_info">
          <?php echo $crypt->decode($card['title']); ?>
          <a href="javascript: q();" data-pwd-id='<?php echo $card['id'];?>' id="get_pwd">Отрыть карточку</a>
        </div>
      <?php endforeach; ?>
  <?php endif; ?>
  </div>
  <form class="save_new_pwd" action="javascript: q();" method="post">
    <legend>Добавить пароль</legend>
    <input type="text" name="pwd_title" value="" placeholder="Назначение:">
    <input type="text" name="val_login" value="" placeholder="Логин: ">
    <input id="pwd_data" type="password" name="val_pwd" value="" placeholder="Пароль:">
    <a href="javascript: q()" id="view_pwd">показать пароль</a>
    <button type="submit" name="button" id="save_new_pwd">Сохранить</button>
    <a id="clean">Очистить форму</button>
  </form>
</div>
