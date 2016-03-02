<meta charset="UTF-8">
<?php
session_start();
if (isset($_SESSION['authorized']))
    {
    $user_id=$_SESSION['user_id'];
    $user_name=$_SESSION['user_name'];
    $user_level=$_SESSION['user_level'];
    echo "Привет $user_name! Вы авторизованы!<br>";
    echo '<a href=feed_form.php>Добавить новую заявку</a>';
    
    }
else
    {
    echo 'Для просмотра заявок авторизуйтесь<br>';
    echo '<a href=login.php>Форма авторизации</a>';
    }
    
    
if(isset($_POST['log_out'])){
    unset ($_SESSION['authorized']);
    unset ($_SESSION['user_id']);
    unset ($_SESSION['user_name']);
    unset ($_SESSION['user_level']);
    session_destroy();
}
?>


<form method="POST" action="my_claims.php">
<input type="submit" value="Выйти" name="log_out" />
</form>
