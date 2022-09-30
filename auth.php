<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <?php
  if(!isset($_COOKIE['user'])){
      $website_title ='Авторизация пользователя';
  }
  else {
    $website_title ='Кабинет пользователя';
  }
  require 'blocks/head.php'; ?>
</head>
<body>
  <?php require 'blocks/header.php'; ?>

  <main class="container mt-5">
    <div class="row">
      <div class="col-md-8 mb-3">
        <?php
          if(!isset($_COOKIE['user'])):
        ?>
        <h4>Форма авторизации</h4>
      <!--  <form action="reg/reg.php" method="post">-->
        <form action="" method="post">
          <lable for="login">Введите логин</lable>
          <input type="text" name="login" id="login" class ="form-control">

          <lable for="pass">Введите пароль</lable>
          <input type="password" name="pass" id="pass" class ="form-control">

          <div class="alert alert-danger mt-2" id="errorBlock"></div>
          <!--<button type="submit" class="btn btn-success mt-5">Зарегистрироваться</button>-->
          <button type="button" id="auth_user" class="btn btn-primary mt-3">Войти</button>
        </form>
        <?php
          else:
        ?>
        
        <?php
          require_once 'mySQLconnect.php';

          $sql = 'SELECT `id_account`, `login`, `status`
          FROM `account`
          WHERE `id_account` = :id';

          $query = $pdo->prepare($sql);
          $query->execute(['id' => $_COOKIE['user']]);
          //$events =  $query->fetchAll(PDO::FETCH_OBJ);
          $customer_acc = $query->fetch(PDO::FETCH_OBJ);

          if($customer_acc->status == 1){
            echo "<ol class=\"raz\">
              <li><h4>Вы удалили свой аккаунт - $customer_acc->login</h4>
              <li>Заказы недоступны, чтобы просмотреть - восстановите учетную запись.
              <span><button class=\"btn btn-danger\" id=\"exit_btn\">Выйти</button></span>
              </ol>";
            echo "<p>Вы можете восстановить свою учетную запись до первого числа следующего месяца.</p>
              <a href='/reg/recovery_acc.php?id_acc=$customer_acc->id_account' title=''>
              <button class=\"btn btn-secondary\" onClick=\"return confirm('Вы подтверждаете восстановление учетной записи?');\">Восстановить аккаунт</button>
              </a>";
            $_SESSION['new_acc_status']=0;
              //заказы не доступны,
          }
          else{
            $_SESSION['new_acc_status']=1;
            //если активный, то выводить заказы
            $sql = 'SELECT account.id_account, `login`, `name_organization`, `contact_person`, `phone`,
            `id_ticket`, `numb_ticket`, `final_price`, ticket.status, `data_event`, `time_event`, `name_event`
            FROM `account`
            JOIN `customer`
            ON account.id_account = customer.id_account
            JOIN `ticket`
            ON  customer.id_customer = ticket.id_customer
            JOIN `schedule`
            ON ticket.id_schedule = schedule.id_schedule_entry
            JOIN `event`
            ON schedule.id_event = event.id_event
            WHERE customer.id_account = :id AND ticket.status = :value';

            $query = $pdo->prepare($sql);
            $query->execute(['id' => $_COOKIE['user'], 'value' => false]);
            //$events =  $query->fetchAll(PDO::FETCH_OBJ);
            $orders = $query->fetchAll(PDO::FETCH_OBJ);

            echo "<ol class=\"raz1\">
            <li><a href='/reg/recovery_acc.php?id_acc=$customer_acc->id_account' title=''>
           <button class=\"btn btn-secondary mb-2\" onClick=\"return confirm('Вы подтверждаете удаление учетной записи?');\">Удалить аккаунт</button></a>
           <span><button class=\"btn btn-danger\" id=\"exit_btn\">Выйти</button></span>
            </ol>";
            echo "<ol class=\"raz\">
              <li><h4>Вы вошли как $customer_acc->login</h4>";


            if(!isset($orders[0]->name_event)){
            echo"<li>У Вас пока нет заказов.
              </ol>";
            }
            else{
              echo"<li>Заказы:
                </ol>";
              foreach ($orders as $order) {
                echo "<h4>$order->name_event, $order->data_event в $order->time_event</h4>
                <p>Заказчик: $order->name_organization $order->contact_person, $order->phone</p>
                <p>Билетов - $order->numb_ticket, Цена - $order->final_price</p>

                <a href='/reg/delete_tick.php?id_ord=$order->id_ticket' title=''>
                <button class='btn btn-secondary mb-5' onClick=\"return confirm('Вы подтверждаете отмену заказа?');\">Отменить заказ</button>
                </a>
                ";
            }
          }
        }
         ?>

        <?php
          endif;
        ?>
      </div>
      <aside class="col-md-4">
        <div class="p-3 mb-3 bg-yellow rounded">
          <h4><b>Новости</b></h4>
          <p>На базе планетария также работает буфет, так что теперь наши посетители могу утолить не только интеллектуальный, но и физический голод.</p>
          <p>Часы работы в будние дни: 10:00 - 19:00</p>
        </div>
        <div class="p-3 mb-3">
          <img class="img-thumbnail" src="/img/pelmeny.jpg" >
        </div>
      </aside>
    </div>
  </main>
  <?php require 'blocks/footer.php'; ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

  <script>
    $('#auth_user').click(function (){
    //  alert("sdr"); //вывод сообщения
      var login = $('#login').val(); //принимаем знач по id
      var passw = $('#pass').val();

      $.ajax({
        url: 'reg/auth.php',
        type: 'POST',
        cache: false, //кэширование
        data: {'login': login, 'pass': passw},
        dataType: 'html', //способ получения данных
        success: function(data) { //обработается, когда получим ответ от сервера
          if(data == 'Готово'){
            $('#auth_user').text('Вы вошли');
            $('#errorBlock').hide();
            document.location.reload(true);
          }
          else {
            $('#errorBlock').show();
            $('#errorBlock').text(data);
          }
        }
      });
    });

    $('#exit_btn').click(function (){
      $.ajax({
        url: 'reg/exit.php',
        type: 'POST',
        cache: false, //кэширование
        data: {},
        dataType: 'html', //способ получения данных
        success: function(data) { //обработается, когда получим ответ от сервера
            document.location.reload(true);
        }
      });
    });

    $('#recovery_btn').click(function (){

      $.ajax({
        url: 'reg/recovery_acc.php',
        type: 'POST',
        cache: false, //кэширование
        data: {},
        dataType: 'html', //способ получения данных
        success: function(data) { //обработается, когда получим ответ от сервера
          if(data == 'Готово'){
            document.location.reload(true);
          }
        }
      });
    });

  </script>

</body>
</html>
