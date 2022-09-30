<?php session_start();?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <?php
  $website_title ='Поиск мероприятий';
  require 'blocks/head.php'; ?>

</head>
<body>
  <?php require 'blocks/header.php'; ?>

  <main class="container mt-5">
    <div class="row">
      <div class="col-md-8 mb-3">
        <?php
        $date_now = new DateTime();
        $date_now->modify('+3 hour'); //Дата и время сейчас
        echo "<h3>Поиск мероприятий</h3>
        <p>Укажите параметры, чтобы найти интересующее Вас мероприятие.</p>
        <p>".$date_now->format('Сегодня d/m/Y')."</p>"; //Время - H:i:s

          require_once 'mySQLconnect.php';

          $sql = 'SELECT `name_event_type` FROM `event_type`';
          $query = $pdo->query($sql);
          $name_ev_type = $query->fetchAll(PDO::FETCH_OBJ); //Названия типов мероприятий

          $date_str = $date_now->format('d.m.Y'); //Строка сегодняшней даты
          $time_str = $date_now->format('H:i'); //Строка с временем сейчас

          ?>

          <lable for="date" class="mr-3">Выберите дату</lable>
          <?php
          $month =['01'=>' января','02'=>' февраля', '03'=>' марта', '04'=>' апреля', '05'=>' мая', '06'=>' июня',
           '07'=>' июля', '08'=>' августа', '09'=>' сентября', '10'=>' октября', '11'=>' ноября', '12'=>' декабря'];

          $select_month = new DateTime();
          $select_month->modify('+3 hour');
           echo " <select method=\"POST\" action=\"\" name=\"date\" id=\"date\">"; //search?
           for($i=0; $i<31; $i++) {
             echo "<option value=\"".$select_month->format('d.m')."\">".$select_month->format('d').$month[$select_month->format('m')];
             $select_month->modify('+1 day');
           }
           echo "</select><br><br>";
           ?>

           <lable for="time" class="mr-3">Выберите примерное время похода в планетарий</lable>
           <?php
            echo " <select method=\"POST\" action=\"\" name=\"time\" id=\"time\" class=\"mr-3\">
              <option value=\"10:00-13:00\">10:00 - 13:00
              <option value=\"13:00-17:00\">13:00 - 17:00
              <option value=\"17:00-21:00\">17:00 - 21:00
              <option value=\"10:00-21:00\">Весь день
            </select>";

            ?>
            <button type="button" id="seach" class="btn btn-info mt-3 mb-2">Узнать расписание</button>

      <div class="jumbotron" id="search_res">

          <?php
          if((!isset($_SESSION['sched_date_time'][0]->time_event)&&isset($_SESSION['sched_date'])&&isset($_SESSION['sched_time']))):
          ?>
              <?php
              $select_date = (string)$_SESSION['sched_date'];
              $select_time = (string)$_SESSION['sched_time'];
              echo "<h4>Мероприятий на ".$select_date." в ".$select_time." не найдено.</h4>";

              ?>
              <?php
                else :
                if(isset($_SESSION['sched_date_time'])&& $_SESSION['sched_date_time']!="" && $_SESSION['sched_date']!="" && $_SESSION['sched_time']!=""){
              ?>
              <!-- Вывод найденных записаей -->
            <form action="" method="post">
              <?php

                $select_date = $_SESSION['sched_date'];
                $select_time = $_SESSION['sched_time'];
                $ses =$_SESSION['sched_date_time'][0]->time_event;
                echo "<h4>Найденные мероприятия на ".$select_date." в ".$select_time."</h4>";

                $schedule_date=$_SESSION['sched_date_time'];
                $i=0;
                foreach($schedule_date as $event_data){
                  echo "<h2>$event_data->name_event</h2>
                    <p>$event_data->data_event</p>
                    <p>$event_data->name_room</p>";
                    echo "<input type='submit' name=\"$i\" value='$event_data->time_event' class='btn btn-info mr-3 mt-3 mb-3'/><br>";

                    $i++;
                }
               ?>
             </form>

             <?php
             for($i = 0; $i < count($schedule_date); $i++){
               if(isset($_POST[$i])){

                 $sql = 'SELECT `id_event`, `data_event`, `time_event`, SUM(`numb_ticket`) AS `numb`
                  FROM `ticket`
                  JOIN `schedule`
                  ON ticket.id_schedule = schedule.id_schedule_entry
                  WHERE `data_event` = :data AND `time_event` = :event_t AND `id_event` = :id
                   GROUP BY `id_event` AND `data_event` AND `time_event`';
                 $query = $pdo->prepare($sql);

                 $query->execute(['data' => $schedule_date[$i]->data_event,
                  'event_t'=> $schedule_date[$i]->time_event,
                   'id'=>$schedule_date[$i]->id_event]);

                 $_SESSION['numb_order_tick'] = $query->fetch(PDO::FETCH_OBJ);

                 if(isset($_SESSION['numb_order_tick']) && $_SESSION['numb_order_tick']!=""){
                   $_SESSION['numb_free_tick'] = $schedule_date[$i]->numb_seats - $_SESSION['numb_order_tick']->numb; //количество доступных для заказа билетов
                 }
                 else{
                   $_SESSION['numb_free_tick'] = $schedule_date[$i]->numb_seats;
                 }
                // $_SESSION['ev_for_tick']=$schedule_date[$i];
                // $_SESSION['ind']=$i;
                 $event_data = $schedule_date[$i];
                 $numb_tick = $_SESSION['numb_free_tick']; //количество доступных для заказа билетов

                 $sql = 'SELECT schedule.id_event, `name_event`
                  FROM `schedule`
                  JOIN `event`
                  ON schedule.id_event = event.id_event
                  WHERE schedule.id_event = :id';
                 $query = $pdo->prepare($sql);
                 $query->execute(['id'=>$schedule_date[$i]->id_event]);

                 $name = $query->fetch(PDO::FETCH_OBJ);

                 echo "<ol class=\"raz\">";

                if($_SESSION['numb_free_tick'] != 0){
                   echo "<li>Количество оставшихся свободных мест на ".$name->name_event." в ".$event_data->time_event." - ".$numb_tick."<br>
                <span><a href='/order_tick.php?id_schedule=$event_data->id_schedule_entry&back_time=1' title='$event_data->name_event'>
                   <button class='btn btn-primary mr-3 mt-3 mb-3'>Заказать билеты</button>
                     </a></span>";
                 }
                 else{
                   echo "<p>Увы! На ".$name->name_event." в ".$event_data->time_event." свободных мест нет, можете выбрать другое время</p>";
                 }
                 echo "</ol>";
               }
             }
           }?>

        <?php
          endif;
        ?>
        </div>
      </div>

      <?php require 'blocks/aside.php'; ?>
    </div>
  </main>

  <?php require 'blocks/footer.php'; ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

  <script>
    $('#seach').click(function (){
    //  alert("sdr"); //вывод сообщения
      var select_date = $('#date').val(); //принимаем знач по id
      var select_time = $('#time').val();

      $.ajax({
        url: 'reg/search_event.php',
        type: 'POST',
        cache: false, //кэширование
        data: {'date': select_date, 'time': select_time},
        dataType: 'html', //способ получения данных
        success: function(data) { //обработается, когда получим ответ от сервера
          //console.log(data);
        //  console.log('hello');
            if(data==true)
            {
              $('#search_res').text("");
              document.location.reload(true);
            }
            else{
              $('#search_res').text(data);
            }
          //  $('#search_res').text(data);
          //  document.location.reload(true);
        }
      });
    });

  </script>
</body>
</html>
