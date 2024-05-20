<?php
    session_start();
    $title = "Ошибка";
    require "header.php";

    $mysql = new mysqli('localhost', 'root', '', 'lab7');

    if ($mysql->connect_error) {
        die("Connection failed: " . $mysql->connect_error);
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
        $pass = md5($pass . "jklwenf340i12e1sqadas");
    
        $query_log = "SELECT * FROM users WHERE login = ?";
        $stmt_log = $mysql->prepare($query_log);
        $stmt_log->bind_param("s", $login);
        $stmt_log->execute();
        $result_log = $stmt_log->get_result();
    
        $query_mail = "SELECT * FROM users WHERE mail = ?";
        $stmt_mail = $mysql->prepare($query_mail);
        $stmt_mail->bind_param("s", $email);
        $stmt_mail->execute();
        $result_mail = $stmt_mail->get_result();
    
        if ($result_log->num_rows > 0) {
            echo "<h1>Логин уже зарегистрирован в системе</h1>";
            exit();
        }
        elseif($result_mail->num_rows > 0) {
            echo "<h1>Почта уже зарегистрирована в системе</h1>";
            exit();
        }
        else {
            $query = "INSERT INTO `users` (`name`, `login`, `password`, `mail`) VALUES(?, ?, ?, ?)";
            $stmt = $mysql->prepare($query);
            $stmt->bind_param("ssss", $name, $login, $pass, $email);
            $stmt->execute();
            header("Location: admin_menu.php");
        }
    }
    
    $mysql->close();

    require "footer.php";