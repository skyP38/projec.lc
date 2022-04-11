<?php

setcookie('atoken', sha1(md5(rand(1000000000, 9999999999))), time()+60*60*24*30, '/');
setcookie('dtoken', sha1(md5(rand(1000000000, 9999999999))), time()+60*60*24*30, '/');

?>
