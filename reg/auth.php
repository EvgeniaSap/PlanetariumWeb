<?php
  $userlogin = trim(filter_var($_POST['login'], FILTER_SANITIZE_STRING));
  $userpass = trim(filter_var($_POST['pass'], FILTER_SANITIZE_STRING));

  $error = '';
  if(strlen($userlogin) <= 3){
    $error = 'Введите логин';
  }
  else if(strlen($userpass) <= 3){
    $error = 'Введите пароль';
  }

  if($error != ''){
    echo $error; //передаем ошибку
    exit();
  }

  $hash = "iutYr1dcv2b7dsCv46blg2fhD";
  $pass = md5($userpass . $hash); //шифрует пароль

  require_once '../mySQLconnect.php';

  $sql = 'SELECT `id_account`, `status` FROM `account` WHERE `login` = :login && `password` = :passw';
  $query = $pdo->prepare($sql); //возвращает объект
  $query->execute(['login'=> $userlogin, 'passw'=> $pass]);

  $user = $query->fetch(PDO::FETCH_OBJ);
  if($user == NULL)
    echo 'Такого пользователя не найдено';
  else{
    setcookie('user', $user->id_account, time() + 3600 * 24 * 30, "/");
    setcookie('status', $user->status, time() + 3600 * 24 * 30, "/");
    echo 'Готово';
  }

 ?>
