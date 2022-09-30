<!DOCTYPE html>
<html lang="ru">
<head>
  <?php
  $website_title ='Мероприятия';
  require 'blocks/head.php'; ?>
</head>
<body>
  <?php require 'blocks/header.php'; ?>

  <main class="container mt-5">
    <div class="row">
      <div class="col-md-8 mb-3">
        <?php
        echo "<h2 class=\"mx-auto\" style=\"width: 200px;color: #17a2b8;text-shadow: #333 -1px -1px 0,
              #fff 1px 1px 0;\">";
        if($_GET['id_type']==1){
          echo"Лекции</h2>";
        }
        else if($_GET['id_type']==2){
          echo"Экскурсии</h2>";
        }
        else if($_GET['id_type']==3){
          echo"Фильмы</h2>";
        }
        else{
          echo"Лабораторные занятия</h2>";
        }

          require_once 'mySQLconnect.php';

          $sql = 'SELECT `id_event`, `name_event`, `name_event_type`, event.id_event_type
          FROM `event`
          JOIN `event_type`
          ON event.id_event_type = event_type.id_event_type
          WHERE event.id_event_type = :id';
          $query = $pdo->prepare($sql);
          $query->execute(['id' => $_GET['id_type']]);

          echo "<ol class=\"raz1\">";
          while($row = $query->fetch(PDO::FETCH_OBJ)){
            echo "<li><h3>$row->name_event</h3>
            <a href='/schedule.php?id=$row->id_event' title='$row->name_event'>
            <span><button class='btn btn-info mb-5'>Посмотреть расписание</button>
            </span></a>";
          }
          echo "</ol>";

         ?>
      </div>

      <?php require 'blocks/aside.php'; ?>
    </div>
  </main>

  <?php require 'blocks/footer.php'; ?>
</body>
</html>
