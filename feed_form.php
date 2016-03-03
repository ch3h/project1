<meta charset="UTF-8">
<?php
session_start();
$link = mysqli_connect("localhost", "root", "", "test");
mysqli_set_charset($link, "utf8");
if (mysqli_connect_errno()) {
        echo "Failed connect to MySQL. ".mysqli_connect_error();
    }
if (isset($_SESSION['authorized']))
    {
    create_form();    
    if(isset($_POST['send'])){
        $claim_name=sanitazeString($_POST['claim_name']);
        $claim_phone=sanitazeString($_POST['claim_phone']);
        $claim_description=sanitazeString($_POST['claim_description']);
        if ($claim_name!= "" && $claim_phone!= ""&& $claim_description != "")
            { 
            if (preg_match("/[0-9]+$/",$claim_phone)){
                if(strlen($claim_description)>=10){
                    $query="INSERT INTO `claims`("
                        . "`claim_id`,"
                        . "`claim_name`,"
                        . "`claim_phone`, "
                        . "`claim_description`, "
                        . "`claim_image`, "
                        . "`claim_date_reg`, "
                        . "`user_id`) VALUES "
                        . "('',"
                        . "'$claim_name',"
                        . "'$claim_phone',"
                        . "'$claim_description',"
                        . "'',"
                        . "unix_timestamp(),"
                        . "'$_SESSION[user_id]')";
                    if (mysqli_query($link, $query))
                        {
//                        $_SESSION['last_id'] = mysqli_insert_id($link); 
                        header("Location:my_claims.php"); 
                        }
                    else {echo 'Увы, произошло что-то непредвиденное';}
                    }
                else {
                    echo 'Длина описания должна быть не менее 10 символов';
                    }
                
                }
            else {
                echo 'Ваш номер должен состоять только из цифр';   
                }
            }
        else {
            echo 'Обязательные поля не должны быть пустыми';
            }       
        } 
    }
if (empty($_SESSION['authorized']))
    {
    echo 'Вам необходимо авторизоваться<br>';
    echo '<a href=login.php>Форма авторизации</a>';
    }
function sanitazeString($var)
{
    $var = stripslashes($var);
    $var = htmlentities($var);
    $var = strip_tags($var);
    return $var;    
}
if(isset($_POST['log_out'])){
    unset ($_SESSION['authorized']);
    unset ($_SESSION['user_id']);
    unset ($_SESSION['user_name']);
    unset ($_SESSION['user_level']);
    session_destroy();
    }
function create_form()
    {
    echo '<form method="POST" action="feed_form.php">
    <p><strong>*Название заявки:</strong></p>
    <p><input type="text" name="claim_name" placeholder="Название заявки"> </p>
    <p><strong>*Контактный телефон:</strong></p>
    <p><input type="tel" name="claim_phone" placeholder="Номер телефона"></p>
    <p><strong>*Описание проблемы:</strong></p> 
    <p><textarea name="claim_description" placeholder="Опишите вашу проблему" cols="70" rows="15"></textarea></p>
    <p><strong>Загрузить изображение</strong></p> 
    <p><input type = "file" name="claim_image" accept ="image/jpeg,image/png"/></p>
    <p>* Отмечены обязательные поля</p> 
    <input type="submit" value="Отправить заявку" name="send" />
    </form>
    <form method="POST" action="feed_form.php">
    <input type="submit" value="Выйти" name="log_out" />
    </form>';
    }
?>

