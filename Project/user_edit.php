<?php
    session_start();
    $title = "Изменение пользователя";
    require "header.php";

    $mysql = new mysqli('localhost', 'root', '', 'lab7');

    if ($mysql->connect_error) {
        die("Не удалось установить соединение: " . $mysql->connect_error);
    }

    // Получение текущих данных пользователя
    $id = $_SESSION['id'];
    $select_query = "SELECT * FROM users WHERE id='$id'";
    $result = $mysql->query($select_query);
    $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Редактировать профиль</title>
</head>
<body>
    <h2>Редактировать профиль</h2>
    <div class='container'>
        <form action="" method="post">
            <input type="hidden" name="id" class="form-control" value="<?php echo $row['id']; ?>">
            <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" placeholder="Имя" required minlength="2" pattern="[а-яА-Яa-zA-Z]+" title="Допустимы только английские и русские буквы">
            <input type="text" name="login" class="form-control" value="<?php echo $row['login']; ?>" placeholder="Логин" required minlength="5" pattern="[a-zA-Z]+" title="Допустимы только английские буквы">
            <input type="email" name="email" class="form-control" value="<?php echo $row['mail']; ?>" placeholder="Email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
            <input type="password" name="old_password" class="form-control" placeholder="Старый пароль">
            <input type="password" name="new_password" class="form-control" placeholder="Новый пароль" minlength="8">
            <input type="password" name="confirm_password" class="form-control" placeholder="Подтвердите новый пароль">
            <button type="submit" name="submit" class="jump_button">Сохранить изменения</button>
            <button href="user_menu.php" class="jump_button">Вернуться на страницу пользователя</button>
        </form>
    </div>
</body>
</html>

<?php
// Обработка данных из формы при отправке
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка соответствия введенного старого пароля текущему паролю пользователя
    if (!empty($old_password)) {
        $hashed_old_password = md5($old_password .  "jklwenf340i12e1sqadas"); // Предполагаем, что в базе данных пароль хранится в зашифрованном виде
        if ($hashed_old_password != $row['password']) {
            echo "<h1>Старый пароль неверен</h1>";
            exit;
        }
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

    // Если логин и email не были изменены или не вызвали ошибки, выполнить обновление данных в базе данных
    $update_password_stmt = null;
    if (!empty($new_password)) {
        // Проверка совпадения нового пароля и его подтверждения
        if ($new_password != $confirm_password) {
            echo "<h1>Новый пароль и подтверждение не совпадают</h1>";
            exit;
        }
        // Обновление пароля пользователя в базе данных
        $hashed_new_password = md5($new_password .  "jklwenf340i12e1sqadas"); // Хэширование нового пароля перед сохранением в базу данных
        $update_password_stmt = $mysql->prepare("UPDATE users SET password=? WHERE id=?");
        $update_password_stmt->bind_param("si", $hashed_new_password, $id);
        $update_password_stmt->execute();
    }

    // Обновление данных пользователя в базе данных, исключая пароль
    $update_user_stmt = $mysql->prepare("UPDATE users SET name=?, mail=?, login=? WHERE id=?");
    $update_user_stmt->bind_param("sssi", $name, $email, $login, $id);
    $update_user_stmt->execute();

    // Обновление данных в сессии для текущего пользователя
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['login'] = $login;

    if ($update_password_stmt !== null) {
        $update_password_stmt->close();
    }

    $update_user_stmt->close();
    $mysql->close();

    header("Location: user_menu.php");
    exit;
    }
    require "footer.php";
?>
