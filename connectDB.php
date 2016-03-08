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
$login_query=$link->prepare("SELECT user_id, user_pass, user_name, user_level FROM users WHERE user_login=?");//Запрос на авторизацию
$login_query->bind_param('s', $login);
if(isset($_SESSION['user_id']))//без этой проверки подготовка запросов происходит до того как в сессии будет создано 'user_id'
    {
    if(isset($_SESSION['file_route']))//в зависимости от того будет ли приложено фото подготавливаюстя разные запросы
        {
        $insert_claims_query=$link->prepare("INSERT INTO `claims`
                                (`claim_id`,`claim_name`,
                                `claim_phone`, `claim_description`,
                                `claim_image`,`claim_date_reg`,
                                `user_id`) 
                                VALUES 
                                ('',?,?,?,?,unix_timestamp(),
                                '$_SESSION[user_id]')");//Добавление заявки в БД
        $insert_claims_query->bind_param('siss', $_SESSION['claim_name'],$_SESSION['claim_phone'],$_SESSION['claim_description'],$_SESSION['file_route']);
        }
    else
        {
        $insert_claims_query=$link->prepare("INSERT INTO `claims`
                                (`claim_id`,`claim_name`,
                                `claim_phone`, `claim_description`,
                                `claim_image`,`claim_date_reg`,
                                `user_id`) 
                                VALUES 
                                ('',?,?,?,'',unix_timestamp(),
                                '$_SESSION[user_id]')");//Добавление заявки в БД
        $insert_claims_query->bind_param('sis', $_SESSION['claim_name'],$_SESSION['claim_phone'],$_SESSION['claim_description']);
        }
}
?>

