<?php 
  $id_order  = $_GET['id_ord'];
  require_once '../mySQLconnect.php';
//update поля
echo $id_order. '<br>';
  $sql = 'UPDATE `ticket` SET `status` = :stat WHERE `id_ticket` = :id';

  $query = $pdo->prepare($sql);
  $query->execute(['stat' => true, 'id' => $id_order]);
//$events =  $query->fetchAll(PDO::FETCH_OBJ);
  header("Location: /auth.php");
?>
