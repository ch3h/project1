<meta charset="UTF-8">
<?php
session_start();
$link = new mysqli("localhost", "root", "","test");
mysqli_set_charset($link, "utf8");
/* проверка соединения */
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться:", mysqli_connect_error());
    exit();
}
?>
