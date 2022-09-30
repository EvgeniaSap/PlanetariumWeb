<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
    <!-- чтобы браузер понял, что пошел php -->
    <?php
//вывод текста
echo '<b>OOooOo</b>' . 'ttt<br>';
echo '\'<br>'."\"<br>".'\\<br>';
$number =7; echo $number.'<br>'; //переменная
echo defined("PI").'<br>'; //ложь-пустая строка
define("PI",3.14);//константа
echo PI.'<br>';
echo defined("PI").'<br>';
echo $number==PI ? 'правда'.'<br>' : 'ложь'.'<br>';
// === проверка эквивалентности (не только на знач, но и на тип данных)
 $arr_1 = array(2, 4, 'sdfb', 7.8, true);
$arr_2 = [1,3,'wsdf'];
echo $arr_2[2].'<br>';
     ?>
</body>
</html>
