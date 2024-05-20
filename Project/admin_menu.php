<?php
    session_start();
    $title = "Меню администратора";
    require "header.php";
?>

<h1>Меню администратора</h1>

<div class='container'>
    <form action="add.php" method="post">
        <input type="text" class="form-control" name="name" id="name" placeholder="Введите имя" required minlength="2" pattern="[а-яА-Яa-zA-Z]+" title="Допустимы только английские и русские буквы">
        <input type="text" class="form-control" name="login" id="login" placeholder="Введите логин" required minlength="5" pattern="[a-zA-Z]+" title="Допустимы только английские буквы">
        <input type="password" class="form-control" name="pass" id="pass" placeholder="Введите пароль" required minlength="8">
        <input type="email" class="form-control" name="email" id="email" placeholder="Введите почту" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
        <button class="jump_button" type="submit">Зарегистрировать пользователя</button>
        <button class="jump_button"><a href="exit.php">Выйти из аккаунта</a></button>
    </form>
</div>

<h1>Пользователи</h1>

<table>
    <tr>
        <th>Имя</th>
        <th>Логин</th>
        <th>Email</th>
        <th>Дата регистрации</th>
        <th>Уровень доступа</th>
        <th>Управление пользователями</th>
    </tr>
    <?php
        $mysql = new mysqli('localhost', 'root', '', 'lab7');
        $result = $mysql->query("SELECT * FROM users");

        if ($mysql->connect_error) {
            die("Connection failed: " . $mysql->connect_error);
        }

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['name']."</td>";
            echo "<td>".$row['login']."</td>";
            echo "<td>".$row['mail']."</td>";
            echo "<td>".$row['registration_date']."</td>";
            echo "<td>".($row['role'] == 1 ? 'Админ' : 'Пользователь')."</td>";
            echo "<td>";
            if ($row['role'] != 1) {
                echo "<a href='delete.php?id=".$row['id']."' class='delete-btn'>Удалить</a>";
            }
            echo "<a href='admin_edit.php?id=".$row['id']."' class='edit-btn'>Редактировать</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        $mysql->close();
    ?>
</table>

<?php
    require "footer.php";
?>