<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <?php
    $user = 'root';
    $password ='';
    $db = 'planetarium';
    $host = 'localhost';
    //само подключение к бд
    $dsn = 'mysql:host='.$host.';dbname='.$db;
    $dsn_Option = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]; //вывод ошибок с бд
    $pdo = new PDO($dsn, $user, $password, $dsn_Option);

//выборка данных
    $query = $pdo->query('SELECT * FROM `account` ORDER BY `id_account` DESC LIMIT 2');
    //выводим с помощью массива
    //while($row = $query->fetch(PDO::FETCH_ASSOC)) {
    //  echo'<h1>'. $row['login'].'</h1><p><b>Password:</b> '. $row['password']. '</p>';
    //}
    //вывод с помощью объектов
    while($row = $query->fetch(PDO::FETCH_OBJ)) { //fetch достает 1 объект
      echo '<h1>'. $row->login. '</h1>
      <p><b>Password:</b> '. $row->password. '</p>';
    }
    //pdo обеспечивает защиту - не передаем прямые данные в запросы
    $log = 'customer1';
    $sql = 'SELECT `login`, `id_account`, `password` FROM `account` WHERE `login` = :name'; //можно знак ?
    $query = $pdo->prepare($sql); //возвращает объект
    $query->execute(['name' => $log]); //передаем массив данных, которые мы вставляем в запрос
    $users = $query->fetchAll(PDO::FETCH_OBJ);
  //  var_dump($users); //вывод целого объекта
    foreach($users as $user){
      echo $user->password.'<br>';
    }

//вставка
/*    $log = 'customer3';
    $pass = 'yusbxv23';
    $id_type = 2;
    $sql = 'INSERT INTO account(id_account_type, login, password) VALUES( :id_type, :login, :password)';
    $query = $pdo->prepare($sql); //возвращает объект
  //  $query->execute(['id_type'=> $id_type, 'login' => $log, 'password'=> $pass]); //передаем массив данных, которые мы вставляем в запрос
    //$query = $pdo->prepare('INSERT INTO account(id_type_account, login, password) VALUES( :id_type, :login, :password)');
    if($query->execute(['id_type'=> $id_type, 'login' => $log, 'password'=> $pass])){
      echo '<br> добавление прошло успешно <br>';
    }
    else{
      echo '<br> добавлениу не удалось <br>';
    }*/

//обновить конкретное поле в табличке
/*    $id_ac = 6;
    $log = 'customer5';
    $sql = 'UPDATE `account` SET `login` = :login WHERE `id_account`=:id';
    $query = $pdo->prepare($sql); //возвращает объект
    $query->execute(['login' => $log, 'id'=> $id_ac]); //передаем массив данных, которые мы вставляем в запрос
*/

//удаление записи
/*    $id_ac = 12;
    $sql ='DELETE FROM `account` WHERE `id_account` = ?';
    $query = $pdo->prepare($sql); //возвращает объект
    $query->execute([$id_ac]); //передаем массив данных, которые мы вставляем в запрос
*/

  ?>
</body>
</html>
