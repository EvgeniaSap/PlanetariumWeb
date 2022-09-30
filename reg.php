<!DOCTYPE html>
<html lang="ru">
<head>
<?php
  $website_title ='Регистрация пользователя';
  require 'blocks/head.php'; ?>
</head>
<body>
  <?php require 'blocks/header.php'; ?>

  <main class="container mt-5">
    <div class="row">
      <div class="col-md-8 mb-3">
        <h4>Форма регистрации</h4>
        <p>Логин и пароль должны быть не короче 4-х символов.</p>
      <!--  <form action="reg/reg.php" method="post">-->
        <form action="" method="post">
          <lable for="login">Введите логин:</lable>
          <input type="text" name="login" id="login" class ="form-control">

          <lable for="pass1">Введите пароль:</lable>
          <input type="password" name="pass" id="pass1" class ="form-control">

          <lable for="pass2">Повторно введите пароль:</lable>
          <input type="password" name="pass" id="pass2" class ="form-control">

          <div class="alert alert-danger mt-2" id="errorBlock"></div>
          <!--<button type="submit" class="btn btn-success mt-5">Зарегистрироваться</button>-->
          <button type="button" id="reg_user" class="btn btn-success mt-3">Зарегистрироваться</button>
        </form>
      </div>
      <?php require 'blocks/aside.php'; ?>
    </div>
  </main>
  <?php require 'blocks/footer.php'; ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

  <script>
    $('#reg_user').click(function (){
    //  alert("sdr"); //вывод сообщения
      var log = $('#login').val(); //принимаем знач по id
      var passw1 = $('#pass1').val();
      var passw2 = $('#pass2').val();

      $.ajax({
        url: 'reg/reg.php',
        type: 'POST',
        cache: false, //кэширование
        data: {'login': log, 'pass1': passw1, 'pass2': passw2},
        dataType: 'html', //способ получения данных
        success: function(data) { //обработается, когда получим ответ от сервера
          if(data == 'Готово'){
            $('#reg_user').text('Вы зарегестрированы');
            $('#errorBlock').hide();
            document.location.href = '/auth.php';
          }
          else {
            $('#errorBlock').show();
            $('#errorBlock').text(data);
          }
        }
      });
    });

  </script>

</body>
</html>
