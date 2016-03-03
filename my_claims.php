<meta charset="UTF-8">
<?php
$last_id=0;//на случай отсутствия заявок в таблице
session_start();
$link = mysqli_connect("localhost", "root", "", "test");
mysqli_set_charset($link, "utf8");
if (mysqli_connect_errno()) {
        echo "Failed connect to MySQL. ".mysqli_connect_error();
    }
if (isset($_SESSION['authorized']))
    {
    $user_id=$_SESSION['user_id'];
    $user_name=$_SESSION['user_name'];
    $user_level=$_SESSION['user_level'];
    if ($user_level==50){
        echo "$user_name, ваши заявки:<br><br><br>";
        $query="SELECT claim_id, claim_name, claim_phone, claim_description, claim_date_reg, claim_image FROM claims WHERE `user_id`='$user_id'";
        $query_last_id="SELECT max(claim_id) FROM claims WHERE user_id='$user_id'";
        if ($result_last=mysqli_query($link,$query_last_id)){
            $row_last = mysqli_fetch_assoc($result_last);
            $last_id=$row_last['max(claim_id)'];//id последней добавленной заявки пользователя
            }         
        }
        elseif ($user_level==100) {
            echo '<b>Заявки пользователей:</b><br><br><br>';
            $query="SELECT user_id,claim_id, claim_name, claim_phone, claim_description, claim_date_reg, claim_image FROM claims";
            $query2="SELECT user_login,user_name,users.user_id,claim_id FROM users,claims WHERE claims.user_id=users.user_id";
            $result2 = mysqli_query($link, $query2);
            $query_last_id="SELECT max(claim_id) FROM claims";
            if ($result_last=mysqli_query($link,$query_last_id)){
                $row_last=  mysqli_fetch_assoc($result_last);
                $last_id=$row_last['max(claim_id)'];//id последней любой добавленной заявки (для админа)
                }       
        }
    $result = mysqli_query($link,$query);
    $rows = mysqli_num_rows($result);
    for ($j=0;$j<$rows;++$j)
        {
        $row =  mysqli_fetch_assoc($result);
          if ($user_level==100){
            if ($row['claim_id']==$last_id)
                {echo '<b>';}//Выделение болдом последней добавленной заявки (для админа) 
                $row2 = mysqli_fetch_assoc($result2);
                echo 'Логин пользователя оставившего заявку:'.$row2['user_login'].'<br>';
                echo 'Имя пользователя оставившего заявку:'.$row2['user_name'].'<br>';//Для админа отображается логин и имя пользователя оставившего заявку
            }
                if ($user_level==50 && $last_id==$row['claim_id'])                
                {echo '<b>';}
                echo 'Наименование заявки:'.$row['claim_name'].'<br>';
                echo 'Контактный телефон:'.$row['claim_phone'].'<br>';
                echo 'Описание заявки:'.$row['claim_description'].'<br>';
                echo 'Заявка была добавлена:'.date("d-m-Y\ H:i",$row['claim_date_reg']).'<br>';
                if ($row['claim_id']==$last_id)
                    {echo '</b>';}//Снятие выделения
                if(!empty($result->fetch_assoc['claim_image'])){
                    if ($row['claim_id']==$last_id)
                        {echo '<b>';}
                        echo 'Прилагаемое фото:'.$result->fetch_assoc()['claim_image'].'<br><br><br>';
                    if ($row['claim_id']==$last_id)
                        {echo '</b>';}
                        }
                    else echo '<br><br>';        
        }
    if ($user_level==50){
        echo "<a href=feed_form.php>Добавить заявку</a>";
        }
    create_exit();
    }
else
    {
    echo 'Для просмотра и добавления заявок пожалуйста авторизуйтесь<br>';
    echo '<a href=login.php>Форма авторизации</a>';
    }
        
if(isset($_POST['log_out'])){
    unset ($_SESSION['authorized']);
    unset ($_SESSION['user_id']);
    unset ($_SESSION['user_name']);
    unset ($_SESSION['user_level']);
    session_destroy();
    }
function create_exit()
{
    echo '<form method="POST" action="my_claims.php">
    <input type="submit" value="Выйти" name="log_out" />
    </form>';
}
?>
