<meta charset="UTF-8">
<?php
error_reporting(E_ALL); 
$link = mysqli_connect("localhost", "root", "", "test");
mysqli_set_charset($link, "utf8");
if (mysqli_connect_errno()) {
        echo "Failed connect to MySQL. ".mysqli_connect_error();
    }
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
                    (NULL, 'admin', MD5('password'), 'admin', '100'),
                    (NULL, 'user1', MD5('password'), 'user1', '50'),
                    (NULL, 'user2', MD5('password'), 'user2', '50'),
                    (NULL, 'user3', MD5('password'), 'user3', '50')";
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

mysqli_query($link, $query_create_users);
mysqli_query($link, $query_insert_users);
mysqli_query($link, $query_create_claims);

        

if(isset($_POST['log_in'])) 
    if ($_POST['login'] != "" && $_POST['password'] != "")
        { 		
        $login = $_POST['login']; 
        $password = $_POST['password'];
        $query="SELECT user_id, user_pass, user_name, user_level FROM users WHERE user_login='$login'";
        $result = mysqli_query($link,$query)or trigger_error("Query Failed! SQL: $query - Error: ".mysqli_error(), E_USER_ERROR);
        if (mysqli_num_rows($result)==1) //если нашлась одна строка, значит такой юзер существует в БД 		
            { 			
            $row = mysqli_fetch_assoc($result); 			
            if (md5($password) == $row['user_pass']) //сравниваем хэшированный пароль из БД с хэшированным паролем 						
                {
                session_start();
                $_SESSION['user_id']=$row['user_id'];
                $_SESSION['user_name']=$row['user_name'];
                $_SESSION['user_level']=$row['user_level'];//права пользователя
                $_SESSION['authorized']=1;
                header("Location:my_claims.php");
                } 
            else //если пароли не совпали 			
                { 				
                echo "Неверный логин или пароль"; 
                } 		
            } 		
        else //если такого пользователя не найдено в БД 		
            {    			
            echo "Неверный логин или пароль"; 			
            }
        } 	
    else 	
        { 		
        echo "Поля не должны быть пустыми"; 				
        }
?>  

<form method="POST" action="login.php">
Логин: <input type="text" name="login" /><br>
Пароль: <input type="password" name="password" /><br>
<input type="submit" value="Войти" name="log_in" />
</form>
