<?php
require 'connect_DB.php';
require_once 'functions.php';
error_reporting(E_ALL); 
if(isset($_POST['log_in'])) {
    if ($_POST['login'] != "" && $_POST['password'] != "") {
        $login = $_POST['login'];
        $password = $_POST['password'];
        //Запрос на авторизацию
        $login_query=$link->prepare("SELECT user_id, user_pass, user_name, 
            user_level FROM users WHERE user_login=?");
        $login_query->bind_param('s', $login);
        $login_query->execute();
        $result=$login_query->get_result();
        if (mysqli_num_rows($result)==1) { //если нашлась одна строка, значит такой юзер существует в БД
            $row = mysqli_fetch_assoc($result);
            if (md5($password) == $row['user_pass']) { //сравниваем хэшированный пароль из БД с хэшированным паролем
                $_SESSION['user_id']=$row['user_id'];
                $_SESSION['user_name']=$row['user_name'];
                $_SESSION['user_level']=$row['user_level'];//права пользователя
                $_SESSION['authorized']=1;
                header("Location:my_claims.php");
            }
            else {//если пароли не совпали 
                echo "Неверный логин или пароль";
            }
        }
        else {//если такого пользователя не найдено в БД 
            echo "Неверный логин или пароль";
        }
    } 	
    else { 		
        echo "Поля не должны быть пустыми";
    }
}
//Автосоздание и заполнение таблиц в базе данных TEST
if(isset ($_POST['create_and_insert'])) {
    $query_create_users="CREATE TABLE `users` (
                        `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `user_login` varchar(20) NOT NULL,
                        `user_pass` varchar(32) NOT NULL,
                        `user_name` varchar(20) NOT NULL,
                        `user_level` tinyint(3) unsigned NOT NULL,
                        PRIMARY KEY (`user_id`),
                        UNIQUE KEY `user_login` (`user_login`)
                       ) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8";
    $query_insert_users="INSERT INTO `users`(`user_id`, `user_login`, `user_pass`, `user_name`, `user_level`) 
                        VALUES 
                        (1, 'admin', MD5('password'), 'admin', '100'),
                        (2, 'user1', MD5('password'), 'user1', '50'),
                        (3, 'user2', MD5('password'), 'user2', '50'),
                        (4, 'user3', MD5('password'), 'user3', '50')";
    $query_create_claims="CREATE TABLE `claims` (
                        `claim_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `claim_name` varchar(100) NOT NULL,
                        `claim_phone` bigint(11) unsigned NOT NULL,
                        `claim_description` text NOT NULL,
                        `claim_image` varchar(100) NOT NULL,
                        `claim_date_reg` int(11) unsigned NOT NULL,
                        `user_id` int(11) NOT NULL,
                        PRIMARY KEY (`claim_id`)
                       ) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8";
    $query_insert_claims="INSERT INTO `claims` (`claim_id`, 
                        `claim_name`, `claim_phone`, 
                        `claim_description`, `claim_image`, 
                        `claim_date_reg`, `user_id`) 
                         VALUES 
                         (1, 'Сломался телефон', '9042889432',
                         'Что то с ним не так ', '', 
                         UNIX_TIMESTAMP(), '2'),
                         (2, 'Сломался монитор', '9042889432',
                         'Что то с ним не так ', '', 
                         UNIX_TIMESTAMP(), '2'),
                         (3, 'Сломался холодильник', '9042889432',
                         'Что то с ним не так ', '', 
                         UNIX_TIMESTAMP(), '2'),
                         (4, 'Глючит комп', '9042589377',
                         'Что то с ним не так ', '', 
                         UNIX_TIMESTAMP(), '3'),
                         (5, 'Глючит голова', '9042589377',
                         'Что то с ней не так ', '', 
                         UNIX_TIMESTAMP(), '3'),
                         (6, 'Глючит телефон', '9042589377',
                         'Что то с ним не так ', '', 
                         UNIX_TIMESTAMP(), '3'),
                         (7, 'Нету звука в холодильнике', '9042583332',
                         'Что то с ним не так ', '', 
                         UNIX_TIMESTAMP(), '4'),
                         (8, 'Пропало изображение в микроволновке', '9042583332',
                         'Что то с ней не так ', '', 
                         UNIX_TIMESTAMP(), '4'),
                         (9, 'Флешка не показывает время', '9042583332',
                         'Что то с ней не так ', '', 
                         UNIX_TIMESTAMP(), '4')";
mysqli_query($link, $query_create_users);
mysqli_query($link, $query_insert_users);
mysqli_query($link, $query_create_claims);
mysqli_query($link, $query_insert_claims);
}
?>

<form method="POST" action="index.php">
Логин: <input type="text" name="login" /><br>
Пароль: <input type="password" name="password" /><br>
<input type="submit" value="Войти" name="log_in" />
</form>
<br><br><br>
<form method="POST" action="login.php">
    Создать в БД test таблицы: claims(заявки) и users, заполнить их.<br>
    admin (просмотр всех заявок)<br>
    user1,user2,user3 (добавление и просмотр собственных заявок)<br>
    пароль для всех '<b>password</b>'<br>
<input type="submit" value="ОК" name="create_and_insert" />
</form>
