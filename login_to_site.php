<?php
    session_start();
    $title = "Вход на сайт";
    require "header.php";
?>

<h1>Форма авторизации</h1>
<div class='container'>
    <form action="#" method="post">
        <input type="text" class="form-control" name="login"
        id="login" placeholder="Введите логин">
        <input type="password" class="form-control" name="pass"
        id="pass" placeholder="Введите пароль">
        <button class="jump_button" type="submit">Авторизоваться</button>
    </form>
</div>

<?php

    $mysql = new mysqli('localhost', 'root', '', 'lab7');

    if ($mysql->connect_error) {
        die("Connection failed: " . $mysql->connect_error);
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pass = md5($pass."jklwenf340i12e1sqadas");

        $stmt = $mysql->prepare("SELECT * FROM users WHERE login=?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result(); 
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($pass == $row["password"]) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['login'] = $row['login'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['mail'] = $row['mail'];

                if ($_SESSION['role'] == 1) {
                    header("Location: admin_menu.php");
                }
                else {
                    header("Location: user_menu.php");
                }
                exit;
            }
            else { echo "<h1>Неправильный логин или пароль</h1>"; }
        }
        else { echo "<h1>Неправильный логин или пароль</h1>"; }
        $stmt->close();
    }

    
    $mysql->close();

    require "footer.php";
?>