<?php session_start();
  $select_sort = $_POST['sort'];

  require_once '../mySQLconnect.php';

  $sql_start = 'SELECT `name_event_type`, event.id_event, `name_event`, `price`, `install_date`
  FROM `event_type`
  JOIN `event`
  ON event_type.id_event_type = event.id_event_type
  JOIN `ticket_price`
  ON event.id_event = ticket_price.id_event';

  if($select_sort=='up'){
    $sql = $sql_start.' ORDER BY `price`';
  }
  else{
    $sql = $sql_start.' ORDER BY `price` DESC';
  }
  $query = $pdo->query($sql);
  $sort_ev = $query->fetchAll(PDO::FETCH_OBJ);

  $_SESSION['sort_events'] = $sort_ev;

  if(isset($_SESSION['sort_events'])){
    echo true;
  }
  else{
    echo false;
  }

?>
