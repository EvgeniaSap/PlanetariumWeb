<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<?php
  require_once 'mySQLconnect.php';

  $sql = 'SELECT `id_schedule_entry`, schedule.id_event, `name_event`, `price`, `data_event`, `time_event`, `duration_event`, `numb_seats`
   FROM `schedule`
   JOIN `event`
   ON schedule.id_event = event.id_event
   JOIN `ticket_price`
   ON schedule.id_event = ticket_price.id_event
   WHERE `id_schedule_entry` = :id';
  $query = $pdo->prepare($sql);
  $query->execute(['id' => $_GET['id_schedule']]);

  $schedule_date = $query->fetch(PDO::FETCH_OBJ); // запись одного мероприятия на определенные дату и время
  $_SESSION['schedule_date_time']=$schedule_date;

  $website_title ='Заказ билетов';
  require 'blocks/head.php'; ?>
</head>
<body>
  <?php require 'blocks/header.php'; ?>

  <main class="container mt-5">
    <div class="row">
      <div class="col-md-8 mb-3">

<?php
        if($_GET['back_time']==1){
          echo "<a href='/search_event.php' title=''>Вернуться к выбору времени</a>";
        }
        else{
          echo "<a href='/order.php?id_event=$schedule_date->id_event&data_schedule_event=$schedule_date->data_event' title='$schedule_date->name_event'>Вернуться к выбору времени</a>";
        }
        ?>
         <?php
           if(isset($_COOKIE['user']) && $_COOKIE['status']==0):
         ?>
        <h3>Оформление заказа</h3>

        <form action="" method="post">

    <?php

    $numb_tick = $_SESSION['numb_free_tick']; //количество доступных для заказа билетов

    echo "<h4>$schedule_date->name_event</h4>
            <p>На $schedule_date->data_event в $schedule_date->time_event</p>
            <p>Доступно $numb_tick билетов</p>";
            ?>

          <lable for="type">Выберите тип заказчика</lable>

          <?php
          $sql = 'SELECT * FROM `customers_type`';
          $query = $pdo->query($sql);

          $cust_types = $query->fetchALL(PDO::FETCH_OBJ); //массив, объекты которого - названия типов объектов

           echo " <select method=\"POST\" action=\"\" name=\"type\" id=\"type\">"; //search?
           foreach ($cust_types as $cust_type) {
             echo "<option value=\"$cust_type->id_customers_type\">$cust_type->name_customers_type";
           }
           echo "</select><br><br>";
        ?>

          <lable for="org">Введите название организации</lable>
          <p>(указывается, если заказчиком является организация)</p>
          <input type="text" name="org" id="org" class ="form-control">

          <lable for="person">Введите ФИО контактного лица</lable>
          <input type="text" name="person" id="person" class ="form-control">

          <lable for="phone">Введите контактный телефон</lable>
          <input type="text" name="phone" id="phone" class ="form-control mb-2">

          <lable for="coef">Выберите тип билета</lable>

          <?php
          $sql = 'SELECT * FROM `coefficient`';
          $query = $pdo->query($sql);

          $coef_types = $query->fetchALL(PDO::FETCH_OBJ); //массив, объекты которого - названия типов объектов

           echo " <select method=\"POST\" action=\"\" name=\"coef\" id=\"coef\">";
           foreach ($coef_types as $coef_type) {
             echo "<option value=\"$coef_type->id_coeff\">$coef_type->name_coeff";
           }
           echo "</select><br><br>";
           ?>

          <lable for="tickets">Введите количество заказываемых билетов</lable>
          <input type="text" name="tickets" id="tickets" class ="form-control">


          <div class="alert alert-danger mt-2" id="errorBlock"></div>
          <!--<button type="submit" class="btn btn-success mt-5">Зарегистрироваться</button>-->
          <button type="button" id="ord_tick" class="btn btn-success mt-3">Заказать</button>
        </form>
        <?php
          else:
        ?>
          <h3>Заказ билетов доступен только после входа в личный кабинет с активной учетной записью</h3>
          <p>Воспользуйтесь кнопками в правом верхнем углу.</p>
        <?php
          endif;
        ?>
      </div>
      <?php require 'blocks/aside.php'; ?>
    </div>
  </main>
  <?php require 'blocks/footer.php'; ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

  <script>
    $('#ord_tick').click(function (){
    //  alert("sdr"); //вывод сообщения
      var cust_type = $('#type').val(); //принимаем знач по id
      var organization = $('#org').val();
      var pers = $('#person').val();
      var phone = $('#phone').val();
      var coeff = $('#coef').val();
      var tick = $('#tickets').val();

      $.ajax({
        url: 'reg/order_tick.php',
        type: 'POST',
        cache: false, //кэширование
        data: {'type': cust_type, 'person': pers, 'phone': phone, 'coef': coeff, 'tickets': tick, 'org': organization},
        dataType: 'html', //способ получения данных
        success: function(data) { //обработается, когда получим ответ от сервера
          if(data == 'Готово'){
            $('#ord_tick').text('Заказ оформлен');
            $('#errorBlock').hide();
            document.location.href = '/auth.php';
          }
          else {
            $('#errorBlock').show();
            $('#errorBlock').text(data);
          }
        }
      });
    });

  </script>

</body>
</html>
