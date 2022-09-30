<!DOCTYPE html>
<html lang="ru">
<head>
  <?php
  $website_title ='Планетарий';
  require 'blocks/head.php'; ?>

</head>
<body>
  <?php require 'blocks/header.php'; ?>

  <main class="container mt-5">
    <div class="row">
      <div class="col-md-8 mb-3">
        <?php

        echo "<h2 class=\"mx-auto\" style=\"width: 200px;color: #17a2b8;text-shadow: #333 -1px -1px 0,
              #fff 1px 1px 0;\">Репертуар</h2>";
          require_once 'mySQLconnect.php';

          $sql = 'SELECT `id_event`, `name_event`, `name_event_type` FROM `event` JOIN `event_type` ON event.id_event_type = event_type.id_event_type  ORDER BY `name_event`';
          $query = $pdo->query($sql);
          //$events =  $query->fetchAll(PDO::FETCH_OBJ);
          while($row = $query->fetch(PDO::FETCH_OBJ)){
            echo "<h3>$row->name_event</h3>
            <p>$row->name_event_type</p>
            <a href='/schedule.php?id=$row->id_event' title='$row->name_event'>
            <button class='btn btn-info mb-5'>Посмотреть расписание</button>
            </a>";
          }

         ?>
      </div>

      <?php require 'blocks/aside.php'; ?>
    </div>
  </main>

  <?php require 'blocks/footer.php'; ?>
</body>
</html>
