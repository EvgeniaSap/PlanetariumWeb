<?php
  $userlogin = trim(filter_var($_POST['login'], FILTER_SANITIZE_STRING));
  $userpass1 = trim(filter_var($_POST['pass1'], FILTER_SANITIZE_STRING));
  $userpass2 = trim(filter_var($_POST['pass2'], FILTER_SANITIZE_STRING));

  $error = '';
  if(strlen($userlogin) <= 3){
    $error = 'Введите логин';
  }
  else if(strlen($userpass1) <= 3){
    $error = 'Введите пароль';
  }
  else if(strlen($userpass2) <= 3){
    $error = 'Повторно введите пароль';
  }
  else if($userpass1 != $userpass2){
    $error = 'Повторно введенный пароль не совпадает';
  }

  if($error != ''){
    echo $error; //передаем ошибку
    exit();
  }

  $hash = "iutYr1dcv2b7dsCv46blg2fhD";
  $pass = md5($userpass1 . $hash); //шифрует пароль

  require_once '../mySQLconnect.php';

  $sql = 'SELECT `login` FROM `account`';
  $query = $pdo->query($sql);
  //$events =  $query->fetchAll(PDO::FETCH_OBJ);
  while($row = $query->fetch(PDO::FETCH_OBJ)){
    if($row->login == $userlogin){
      $error = 'Такой логин уже занят';
      echo $error; //передаем ошибку
      exit();
    }
  }

  $sql = 'INSERT INTO account(id_account_type, login, password, status) VALUES( :id_type, :login, :password, :stat)';
  $query = $pdo->prepare($sql); //возвращает объект

  $query->execute(['id_type'=> 2, 'login' => $userlogin, 'password'=> $pass, 'stat'=> 0]);

  echo 'Готово';
 ?>
