<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">

  <nav class ='dws-menu'>
    <ul>
      <li><a href="">Меню</a>
        <ul>
          <li><a href="/"><i>Мероприятия</i></a>
            <ul>
              <li><a href="/events.php?id_type=1"><i>Лекции</i></a>
              <li><a href="/events.php?id_type=2"><i>Экскурсии</i></a>
              <li><a href="/events.php?id_type=3"><i>Кино</i></a>
              <li><a href="/events.php?id_type=4"><i>Лабораторные занятия</i></a>
            </ul>
          </li>
          <li><a href="/search_event.php"><i>Расписание</i></a></li>
          <li><a href="/price_list.php"><i>Цены</i></a></li>
        </ul>
      </li>
    </ul>
  </nav>

  <h5 class="my-0 mr-md-auto font-weight-normal"><a class="p-2 text-dark" href="/">Planetarium</a></h5>
  <nav class="my-2 my-md-0 mr-md-3">
    <!--<a class="p-2 text-dark" href="/">Главная</a>-->
  </nav>

  <?php
    if(!isset($_COOKIE['user'])):
  ?>
  <a class="btn btn-outline-primary mr-2 mb-2" href="/auth.php">Войти</a>
  <a class="btn btn-outline-primary mb-2" href="/reg.php">Регистрация</a>
  <?php
    else:
  ?>
  <a class="btn btn-outline-primary mr-2 mb-2" href="/auth.php">Кабинет пользователя</a>

  <?php
    endif;
  ?>
</div>
