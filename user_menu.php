<?php
    session_start();
    $title = "Меню пользователя";
    require "header.php";

    if(isset($_SESSION['login'])) {
        $username = $_SESSION['name'];

        $mysql = new mysqli('localhost', 'root', '', 'lab7');

        if ($mysql->connect_error) {
            die("Не удалось подключиться к базе данных: " . $mysql->connect_error);
        }

        $stmt = $mysql->prepare("SELECT * FROM `users` WHERE `name` = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        
        echo "<h1>Аккаунт пользователя $username</h1>";
        echo "<table>";
        echo "<tr>";
        echo "<th>Имя</th>";
        echo "<th>Логин</th>";
        echo "<th>Email</th>";
        echo "<th>Дата регистрации</th>";
        echo "<th>Действия</th>";
        echo "</tr>";

        $user = $result->fetch_assoc();
        echo "<tr>";
        echo "<td>".$user['name']."</td>";
        echo "<td>".$user['login']."</td>";
        echo "<td>".$user['mail']."</td>";
        echo "<td>".$user['registration_date']."</td>";
        echo "<td>
                <a href='user_edit.php?id=".$user['id']."' class='edit-btn'>Редактировать</a>
            </td>";
        echo "</tr>";
        echo "</table>";
        echo "<br>";
        echo "<a href='exit.php' class='jump_button'>Выйти из аккаунта</a>";
    

        $stmt->close();

        $mysql->close();
    }
        else {
            echo "<h1>Ошибка: Пользователь не аутентифицирован</h1>";
            echo "<a href='login_to_site.php' class='jump_button'>Вернуться на страницу ввода</a>";
        }

    require "footer.php";