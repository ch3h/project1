<meta charset="UTF-8">
<?php 
//error_reporting(E_ALL); 
$link = mysqli_connect("localhost", "root", "", "simpledb");
mysqli_set_charset($link, "utf8");
if (mysqli_connect_errno()) {
        echo "Failed connect to MySQL. ".mysqli_connect_error();
    }
$error = array();   
if(isset($_POST['log_in'])) 
    if ($_POST['login'] != "" && $_POST['password'] != "") //если поля заполнены 
        { 		
            $login = $_POST['login']; 
            $password = $_POST['password'];
            $query="SELECT user_pass FROM user WHERE user_login=$login";
            echo $query;
            $result = mysqli_query($link,$query) or trigger_error($link->error); //запрашиваем строку из БД с логином, введённым пользователем 
            var_dump($result);
            $rows = mysqli_num_rows($result);
            var_dump($rows);
            
            
            
            if (mysqli_num_rows($result)==1) //если нашлась одна строка, значит такой юзер существует в БД 		
                { 			
                    $row = mysqli_fetch_assoc($result); 			
                    if (md5($password) == $row['user_pass']) //сравниваем хэшированный пароль из БД с хэшированными паролем 						

                        {
                        echo 'Вы авторизованы!';
                        } 
                    else //если пароли не совпали 			
                        { 				
                        echo "Неверный пароль"; 
                        } 		
               } 		
            else //если такого пользователя не найдено в БД 		
               {    			
                   echo "Неверный логин и пароль"; 			
               }
               
       } 	
 

    else 	
	{ 		
           echo "Поля не должны быть пустыми"; 				
        } 
var_dump($error);     
?>  

<form method="POST" action="another_login.php">
Логин: <input type="text" name="login" /><br>
Пароль: <input type="password" name="password" /><br>
<input type="submit" value="Войти" name="log_in" />
</form>