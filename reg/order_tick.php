<?php session_start();
  $user_id = $_COOKIE['user'];
  $user_cust_type = $_POST['type'];
  $name_org = trim(filter_var($_POST['org'], FILTER_SANITIZE_STRING));
  $conact_pers = trim(filter_var($_POST['person'], FILTER_SANITIZE_STRING));
  $phone = trim(filter_var($_POST['phone'], FILTER_SANITIZE_STRING));
  $coeff = $_POST['coef'];
  $tickets = trim(filter_var($_POST['tickets'], FILTER_SANITIZE_STRING));

  $error = '';
  if(strlen($name_org) <=3 && $user_cust_type != 1){
    $error = 'Введите название организации-заказчика';
  }
  else if(strlen($conact_pers) <= 3){
    $error = 'Введите ФИО контактного лица';
  }
  else if(strlen($phone) <= 6){
    $error = 'Введите телефон';
  }
  else if(strlen($tickets) <= 0){
    $error = 'Введите количество билетов';
  }
  else if($tickets > $_SESSION['numb_free_tick']){
    $error = 'Введено больше билетов, чем есть в доступе';
    }

  if($error != ''){
    echo $error; //передаем ошибку
    exit();
  }

  require_once '../mySQLconnect.php';

  $sql = 'INSERT INTO customer(id_account, id_customers_type, name_organization, contact_person, phone)
   VALUES(:id_acc, :id_cust_t, :name_org, :contact_per, :phone)';
  $query = $pdo->prepare($sql);

  $query->execute(['id_acc'=> $user_id, //добавление нового заказчика
   'id_cust_t' => $user_cust_type,
   'name_org' =>  $name_org,
   'contact_per'=> $conact_pers,
   'phone'=> $phone]);

   $sql = 'SELECT `id_customer`
    FROM `customer`
    WHERE `id_account` = :id_acc
    AND `id_customers_type` = :id_cust_t
    AND `name_organization` = :name_org
    AND `contact_person` = :contact_per
    AND `phone` = :phone';
   $query = $pdo->prepare($sql);

   $query->execute(['id_acc'=> $user_id,
    'id_cust_t' => $user_cust_type,
    'name_org' => $name_org,
    'contact_per'=> $conact_pers,
    'phone'=> $phone]);

    $customer = $query->fetch(PDO::FETCH_OBJ); //только что введенный заказчик

    $sql = 'SELECT * FROM `coefficient` WHERE `id_coeff` = :id_coef';
    $query = $pdo->prepare($sql);

    $query->execute(['id_coef'=> $coeff]);

     $coef_value = $query->fetch(PDO::FETCH_OBJ); //значение коеффициента по id

    $sql = 'INSERT INTO ticket(id_customer, id_schedule, id_coeff, numb_ticket, final_price, status)
     VALUES(:id_cust, :id_sched, :id_coef, :numb, :price, :status)';
    $query = $pdo->prepare($sql);

    $f_price= $_SESSION['schedule_date_time']->price * $coef_value->value_coeff * $tickets;

    $query->execute(['id_cust'=> $customer->id_customer, //добавление заказанных билетов
     'id_sched' => $_SESSION['schedule_date_time']->id_schedule_entry,
     'id_coef' => $coeff,
     'numb'=> $tickets,
     'price'=> $f_price,
     'status' => '0']);

  echo 'Готово';
 ?>
