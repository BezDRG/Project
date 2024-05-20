<?php
    session_start();

    $title = "Изменение пользователя";
    require "header.php";

    $mysql = new mysqli('localhost', 'root', '', 'lab7');

    if ($mysql->connect_error) {
        die("Не удалось установить соединение: " . $mysql->connect_error);
    }

    $id = $_GET['id'];

    if(!isset($_GET['id'])) {
        echo "Не указан идентификатор пользователя для редактирования";
        exit();
    }

    $select_query = "SELECT * FROM users WHERE id=?";
    $stmt_select = $mysql->prepare($select_query);
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    
    if ($result->num_rows == 0) {
        echo "Пользователь не найден";
        exit();
    } else {
        $row = $result->fetch_assoc();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование информации о пользователе</title>
</head>
<body>
    <h1>Редактирование информации о пользователе</h1>
    <div class='container'>
        <form action="" method="post">
            <input type="hidden" name="id" class="form-control" value="<?php echo $row['id']; ?>" >
            <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" placeholder="Имя" required minlength="2" pattern="[а-яА-Яa-zA-Z]+" title="Допустимы только английские и русские буквы">
            <input type="text" name="login" class="form-control" value="<?php echo $row['login']; ?>" placeholder="Логин" required minlength="5" pattern="[a-zA-Z]+" title="Допустимы только английские буквы">
            <input type="password" name="password" class="form-control" placeholder="Введите новый пароль" minlength="8">
            <input type="email" name="email" class="form-control" value="<?php echo $row['mail']; ?>" placeholder="Email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
            <select name="role" class="form-control">
                <option value="0" <?php if($row['role'] == 0) echo "selected"; ?>>Пользователь</option>
                <option value="1" <?php if($row['role'] == 1) echo "selected"; ?>>Администратор</option>
            </select>
            <button type="submit" name="submit" class="jump_button">Сохранить изменения</button>
            <button class="jump_button"><a href="admin_menu.php">Вернуться на страницу администратора</a></button>
        </form>
    </div>
</body>
</html>

<?php
    // Проверка отправки формы
    if(isset($_POST['submit'])) {
        $name = $_POST['name'];
        $login = $_POST['login'];
        $email = $_POST['email'];
        $new_password = $_POST['password'];
        $role = $_POST['role'];

        // Проверка, был ли изменён пароль
        if (!empty($new_password)) {
            // Проверяем, изменился ли пароль
            if ($new_password != $row['password']) {
                $pass = md5($new_password . "jklwenf340i12e1sqadas");
            } else {
                // Используем текущий пароль, если он не был изменен
                $pass = $row['password'];
            }
        } else {
            // Если пароль не был введён, используем текущий пароль
            $pass = $row['password'];
        }

        // Проверка наличия нового логина в базе данных, если он был изменен
        if ($login != $row['login']) {
            $query_log = "SELECT * FROM users WHERE login = ?";
            $stmt_log = $mysql->prepare($query_log);
            $stmt_log->bind_param("s", $login);
            $stmt_log->execute();
            $result_log = $stmt_log->get_result();

            if ($result_log->num_rows > 0) {
                echo "<h1>Логин уже зарегистрирован в системе</h1>";
                exit();
            }
        }

        // Проверка наличия новой почты в базе данных, если она была изменена
        if ($email != $row['mail']) {
            $query_mail = "SELECT * FROM users WHERE mail = ?";
            $stmt_mail = $mysql->prepare($query_mail);
            $stmt_mail->bind_param("s", $email);
            $stmt_mail->execute();
            $result_mail = $stmt_mail->get_result();

            if ($result_mail->num_rows > 0) {
                echo "<h1>Почта уже зарегистрирована в системе</h1>";
                exit();
            }
        }

        $stmt_update = $mysql->prepare("UPDATE users SET name=?, login=?, password=?, mail=?, role=? WHERE id=?");
        $stmt_update->bind_param("ssssii", $name, $login, $pass, $email, $role, $id);
        $stmt_update->execute();
        header("Location: admin_menu.php");
    }

    $mysql->close();
    require "footer.php";
?>
