<meta charset="UTF-8">
<?php 
//error_reporting(E_ALL); 
$link = mysqli_connect("localhost", "root", "", "simpledb");
mysqli_set_charset($link, "utf8");
if (mysqli_connect_errno()) {
        echo "Failed connect to MySQL. ".mysqli_connect_error();
    }
if(!isset($_SESSION['user_authorized'])){
    if(isset($_POST['log_in'])) 
        if ($_POST['login'] != "" && $_POST['password'] != "")
            { 		
                $login = $_POST['login']; 
                $password = $_POST['password'];
                $query="SELECT user_id, user_pass, user_name, user_level FROM user WHERE user_login='$login'";
                $result = mysqli_query($link,$query)or trigger_error("Query Failed! SQL: $query - Error: ".mysqli_error(), E_USER_ERROR);
                $rows = mysqli_num_rows($result);

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
    //                        echo "Привет $row[user_name] ! Вы авторизованы!";
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
}
 else {
    header("Location:my_claims.php"); 
}
$link->close();
?>  

<form method="POST" action="login.php">
Логин: <input type="text" name="login" /><br>
Пароль: <input type="password" name="password" /><br>
<input type="submit" value="Войти" name="log_in" />
</form>