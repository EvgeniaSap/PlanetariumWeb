<?php session_start();?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <?php
  $website_title ='Цены';
  require 'blocks/head.php'; ?>

</head>
<body>
  <?php require 'blocks/header.php'; ?>

  <main class="container mt-5">
    <div class="row">
      <div class="col-md-8 mb-3">
        <?php

        echo "<h2 style=\"color: #17a2b8;text-shadow: #333 -1px -1px 0,
              #fff 1px 1px 0;\">Цены в нашем планетарии</h2>";

      echo "<p>Укажите параметр сортировки.</p>
      <select method=\"POST\" action=\"\" name=\"sort\" id=\"sort\" class=\"mr-3\">
        <option value=\"up\">По возрастанию цены
        <option value=\"down\">По убыванию цены
      </select>";
      ?>
      <button type="button" id="sort_btn" class="btn btn-info mt-3 mb-2">Отсортировать</button>

         <div class="jumbotron" id="sort_res">
           <?php
           if(isset($_SESSION['sort_events'])):
           ?>
           <?php
           $sort_events = $_SESSION['sort_events']; //массив мероприятий отсортированных по цене

           foreach ($sort_events as $event) {
             echo "<h4>$event->name_event</h4>
             <p>$event->name_event_type Цена с $event->install_date: $event->price рублей.</p>
             <a href='/schedule.php?id=$event->id_event' title='$event->name_event'>
             <button class='btn btn-info mb-5'>Посмотреть расписание</button>
             </a>";

           }
           ?>

           <?php
             else :
               echo "Ничего не найдено";
          ?>

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
    $('#sort_btn').click(function (){
    //  alert("sdr"); //вывод сообщения
      var select_sort = $('#sort').val(); //принимаем знач по id

      $.ajax({
        url: 'reg/price_list.php',
        type: 'POST',
        cache: false, //кэширование
        data: {'sort': select_sort},
        dataType: 'html', //способ получения данных
        success: function(data) { //обработается, когда получим ответ от сервера
          //console.log(data);
        //  console.log('hello');
            if(data==true)
            {
            //  $('#sort_res').text("");
              document.location.reload(true);
            //  $('#sort_res').text(data);
            }
            else{
              $('#sort_res').text(data);
            }
          //  $('#search_res').text(data);
          //  document.location.reload(true);
        }
      });
    });

  </script>
</body>
</html>
