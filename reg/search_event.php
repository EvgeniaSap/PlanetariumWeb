<?php session_start();
  $select_date = $_POST['date'].'.2019';
  $select_time = $_POST['time'];
  $first_t = (int)mb_strimwidth($select_time, 0, 2, "");
  $last_t = (int)mb_strimwidth($select_time, -5, 2, "");

  require_once '../mySQLconnect.php';

  $sql = 'SELECT `id_schedule_entry`, schedule.id_event, `name_event`, `name_event_type`, `name_room`, `data_event`, `time_event`, `duration_event`, `numb_seats`
   FROM `event_type`
   JOIN `event`
   ON event_type.id_event_type = event.id_event_type
   JOIN `schedule`
   ON event.id_event = schedule.id_event
   JOIN `room`
   ON schedule.id_room = room.id_room
   WHERE `data_event` = :data
   ORDER BY `name_event`';
  $query = $pdo->prepare($sql);
  $query->execute(['data' => $select_date]);

  $schedule_date = $query->fetchAll(PDO::FETCH_OBJ); //массив, объекты которого - записи мероприятий на одну дату, но на разное время

  $schedule_date_time = array();
  if(isset($schedule_date[0]->time_event)){

    for($i=0; $i<count($schedule_date); $i++){
    //  echo $schedule_date[$i]->time_event." ";
      if((int)mb_strimwidth($schedule_date[$i]->time_event, 0, 2, "")>=$first_t && (int)mb_strimwidth($schedule_date[$i]->time_event, 0, 2, "")<=$last_t){
    //    echo $schedule_date[$i]->time_event." ".$first_t." ".$last_t." ";
        //unset($schedule_date[$i]);
        array_push($schedule_date_time, $schedule_date[$i]);
      }
    }

  /*  foreach($schedule_date as $event_data){
          echo $event_data->time_event." ";
        }*/
  }

/*  $i=0;
  foreach($schedule_date as $event_data){
        if((int)mb_strimwidth($event_data->time_event, 0, 2, "")>=$first_t && (int)mb_strimwidth($event_data->time_event, 0, 2, "")<=$last_t){
          $_SESSION['sched_date_time'][0] = $event_data;
        }
        $i++;
}*/
  $_SESSION['sched_date_time'] = $schedule_date_time;

  $_SESSION['sched_date'] = $select_date;

  $_SESSION['sched_time'] = $select_time;

  if(isset($_SESSION['sched_date_time'])&&isset($_SESSION['sched_date'])&&isset($_SESSION['sched_time'])){
    echo true;
    //echo $_SESSION['sched_date_time'][0]->time_event;
  }
  else{
    echo false;
  }

/*    foreach($schedule_date as $event_data){
      echo "<a href='/order.php?id_event=$event_data->id_event&data_schedule_event=$event_data->data_event' title='$event_data->name_event'>
      <button class='btn btn-info mr-3 mt-3 mb-3'>$event_data->data_event</button>
      </a>";*/
    //  echo $event_data->data_time_event.'<br>';

?>
