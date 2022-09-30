<?php
setcookie('user', "", time() - 3600 * 24 * 30, "/");
unset($_COOKIE['user']); //удаление элемента из массива cookie
echo true;
?>
