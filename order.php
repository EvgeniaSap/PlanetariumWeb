<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <?php
    require_once 'mySQLconnect.php';

    $sql = 'SELECT `id_schedule_entry`, schedule.id_event, `name_event`, `name_room`, `data_event`, `time_event`, `duration_event`, `numb_seats`
     FROM `event`
     JOIN `schedule`
     ON event.id_event = schedule.id_event
     JOIN `room`
     ON schedule.id_room = room.id_room
     WHERE `data_event` = :data AND schedule.id_event = :id';
    $query = $pdo->prepare($sql);
    $query->execute(['data' => $_GET['data_schedule_event'], 'id' => $_GET['id_event']]);

    $schedule_date = $query->fetchAll(PDO::FETCH_OBJ); //массив, объекты которого - записи одного мероприятия на одну дату, но на разное время

    $website_title = $schedule_date[0]->name_event;
    require 'blocks/head.php';
   ?>
</head>
<body>
  <?php require 'blocks/header.php'; ?>

  <main class="container mt-5">
    <div class="row">
      <div class="col-md-8 mb-3">
        <?php
        $date_ev = $schedule_date[0];
        echo "<a href='/schedule.php?id=$date_ev->id_event'>Вернуться к расписанию</a>";
         ?>
        <div class="jumbotron">
          <form action="" method="post">
          <?php

          echo "<h2>$date_ev->name_event</h2>
            <p>$date_ev->data_event</p>
            <p>$date_ev->name_room</p>";

          /*  for($i = 0; $i < count($schedule_date); $i++){
               echo "<input name = \"$i\" type = \"submit\" value = \"$i\" />";

               echo "<button type=\"submit\" id=\"$i\" value=\"$event_time->time_event\" class='btn btn-warning mr-3 mt-3 mb-3'>$event_time->time_event</button>";
            }*/
            $i=0;
            foreach($schedule_date as $event_time){ //вывод кнопок со временем проведения мероприятия
            //  echo "<input type='radio' name='time' value='$event_time->time_event'>$event_time->time_event <br>";

              echo "<input type='submit' name=\"$i\" value='$event_time->time_event' class='btn btn-info mr-3 mt-3 mb-3'/>";
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

              $event_data = $schedule_date[$i];
              $numb_tick = $_SESSION['numb_free_tick']; //количество доступных для заказа билетов

              if($_SESSION['numb_free_tick'] != 0){
                echo "Количество оставшихся свободных мест на ".$event_data->time_event." - ".$numb_tick."<br>
                <a href='/order_tick.php?id_schedule=$event_data->id_schedule_entry&back_time=0' title='$event_data->name_event'>
                <button class='btn btn-primary mr-3 mt-3 mb-3'>Заказать билеты</button>
                  </a>";
              }
              else{
                echo "<p>Увы! Свободных мест нет, можете выбрать другое время</p>";
              }
            }
          }
      /*    if(isset($_POST['ev_time'])){

            $sql = 'SELECT `id_event`, `data_event`, `time_event`, SUM(`numb_ticket`) AS `numb`
             FROM `ticket`
             JOIN `schedule`
             ON ticket.id_schedule = schedule.id_schedule_entry
             WHERE `data_event` = :data AND `time_event` = :event_t AND `id_event` = :id
              GROUP BY `id_event` AND `data_event` AND `time_event`';
            $query = $pdo->prepare($sql);
            $query->execute(['data' => $_GET['data_schedule_event'], 'event_t'=> $_POST['ev_time'], 'id'=>$_GET['id_event']]);

            $numb_order_tick = $query->fetch(PDO::FETCH_OBJ);

            $numb_tick = $schedule_date->numb_seats - $numb_order_tick->numb;

            if($numb_tick != 0){
              echo "Количество оставшихся свободных мест: ".$numb_tick."<br>
              <a href='/order_tick.php?id_schedule=$event_data->id_schedule_entry' title='$event_data->name_event'>
              <button class='btn btn-warning mr-3 mt-3 mb-3'>Заказать билеты</button>
                </a>";
              $_SESSION['true_false'] = inline;
            }
            else{
              echo "<p>Увы! Свободных мест нет, можете выбрать другую дату
               <a href='/schedule.php?id=$schedule_date->id_event'>вернуться к расписанию</a></p>";
               $_SESSION['true_false'] = none;
            }
          }*/

        /*if(isset($_POST['я_кнопка'])){
    //запись в базу и т.п.
    $_SESSION['true_false']=true;
}
if($_SESSION['true_false']==false){ отображение формы
  // Ваша форма
}
*/
          ?>
        </div>
      </div>
      <?php require 'blocks/aside.php'; ?>
    </div>
  </main>
  <?php require 'blocks/footer.php'; ?>

</body>
</html>
