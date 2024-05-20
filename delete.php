<?php
    session_start();

    $mysql = new mysqli('localhost', 'root', '', 'lab7');

    if ($mysql->connect_error) {
        die("Connection failed: " . $mysql->connect_error);
    }

    $id = $_GET['id'];
    $delete = "DELETE FROM users WHERE id=$id";
    $result = $mysql->query($delete);
    $mysql->close();

    header('Location: admin_menu.php');