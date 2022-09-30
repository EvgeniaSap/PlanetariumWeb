<!DOCTYPE html>
<html lang="ru">
<head>
  <?php
    require_once 'mySQLconnect.php';

    $sql = 'SELECT `name_event` FROM `event` WHERE `id_event`=:id';
    $query = $pdo->prepare($sql);
    $query->execute(['id' => $_GET['id']]);

    $name_ev = $query->fetch(PDO::FETCH_OBJ);

    $website_title = $name_ev->name_event;
    require 'blocks/head.php';
   ?>
</head>
<body>
  <?php require 'blocks/header.php'; ?>

  <main class="container mt-5">
    <div class="row">
      <div class="col-md-8 mb-3">
        <div class="jumbotron">
          <h1><?=$name_ev->name_event?></h1>
          <?php
          $sql = 'SELECT `id_schedule_entry`,schedule.id_event, `name_event`, `data_event`, COUNT(`time_event`)
          FROM `event`
          JOIN `schedule`
          ON event.id_event = schedule.id_event
          WHERE schedule.id_event = :id
          GROUP BY `data_event`';
          $query = $pdo->prepare($sql);
          $query->execute(['id' => $_GET['id']]);

          $schedule = $query->fetchAll(PDO::FETCH_OBJ);

          if(!isset($schedule[0]->data_event)){
            echo"В ближайшие дни проведение этого мероприятия не планируется.";
          }
          else{
            foreach($schedule as $event_data){
              echo "<a href='/order.php?id_event=$event_data->id_event&data_schedule_event=$event_data->data_event' title='$event_data->name_event'>
              <button class='btn btn-info mr-3 mt-3 mb-3'>$event_data->data_event</button>
              </a>";
            //  echo $event_data->data_time_event.'<br>';
            }
          }
          ?>
        </div>
      </div>

      <?php require 'blocks/aside.php'; ?>
    </div>
  </main>

  <?php require 'blocks/footer.php'; ?>
</body>
</html>
