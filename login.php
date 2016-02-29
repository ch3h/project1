<meta charset="UTF8">
<?php

$connect = new mysqli('localhost', 'root', '', 'simpledb');
 mysqli_set_charset($connect, "utf8");
//    if ($connect->connect_error) {
//        die('Ошибка подключения (' . $connect->connect_errno . ') '
//                . $connect->connect_error);
//    }
// 
//    if (mysqli_connect_error()) {
//        die('Ошибка подключения (' . mysqli_connect_errno() . ') '
//                . mysqli_connect_error());
//    }
    
    
if (isset($_POST['log_in'])) {
     $login = $_POST['login'];
     $password = md5($_POST['password']);
     $query="SELECT user_pass FROM user WHERE user_login=$login;";
     $result = $connect->query($query);
     var_dump($result);
     $row = $result->fetch_assoc();
     var_dump($result);
     
     if ($row == 0) {
        echo "Несуществующий логин или пароль";
     } else {
           if ($row["user_pass"] == $password) {
            echo "Вы авторизованы";
           } 
           else 
           {
            echo "Несуществующий логин или пароль!";
           }
     }
    }
?>

<form method="POST" action="login.php">
Логин: <input type="text" name="login" /><br>
Пароль: <input type="password" name="password" /><br>
<input type="submit" value="Войти" name="log_in" />
</form>