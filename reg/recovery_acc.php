<?php session_start();

  $id_acc  = $_GET['id_acc'];
  $new_stat = $_SESSION['new_acc_status'];
  require_once '../mySQLconnect.php';
//update поля
//echo $id_acc. '<br>';
  $sql = 'UPDATE `account` SET `status` = :stat WHERE `id_account` = :id';

  $query = $pdo->prepare($sql);
  $query->execute(['stat' => $new_stat, 'id' => $id_acc]);
//$events =  $query->fetchAll(PDO::FETCH_OBJ);
  setcookie('status', $new_stat, time() + 3600 * 24 * 30, "/");
  header("Location: /auth.php");

?>
