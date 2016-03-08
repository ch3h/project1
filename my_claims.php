<meta charset="UTF-8">
<?php
require_once 'connectDB.php';
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
            $query2="SELECT user_login,user_name,users.user_id,claim_id FROM users,claims WHERE claims.user_id=users.user_id order by claims.claim_id";
            $result2 = mysqli_query($link, $query2);
            $query_last_id="SELECT max(claim_id) FROM claims";
            if ($result_last=mysqli_query($link,$query_last_id)){
                $row_last=  mysqli_fetch_assoc($result_last);
                $last_id=$row_last['max(claim_id)'];//id последней любой добавленной заявки (для админа)
                }
            
            }
    $result = mysqli_query($link,$query);
    $rows = mysqli_num_rows($result);
    $claimsXML=new SimpleXMLElement("<claims></claims>");
    
    for ($j=0;$j<$rows;++$j)
        {
        $row =  mysqli_fetch_assoc($result);
        if ($user_level==100){
            $row2 = mysqli_fetch_assoc($result2);
            if ($row['claim_id']==$last_id)
                    {echo '<b>';}//Выделение болдом последней добавленной заявки (для админа) 
            echo 'Логин пользователя оставившего заявку:'.$row2['user_login'].'<br>';
            echo 'Имя пользователя оставившего заявку:'.$row2['user_name'].'<br>';//Для админа отображается логин и имя пользователя оставившего заявку
            $claimXML=$claimsXML->addChild('claim');
            $claimXML->addAttribute('id',$row['claim_id']);
            $claim_author_loginXML=$claimXML->addChild('claim');
            $claim_author_loginXML->addAttribute('author_login', $row2['user_login']);
            $claim_author_nameXML=$claimXML->addChild('claim');
            $claim_author_nameXML->addAttribute('author_name', $row2['user_name']);
        }
        if ($user_level==50 && $last_id==$row['claim_id'])                
            {echo '<b>';}
        echo 'Наименование заявки:'.$row['claim_name'].'<br>';
        echo 'Контактный телефон:'.$row['claim_phone'].'<br>';
        echo 'Описание заявки:'.$row['claim_description'].'<br>';
        $date=date("d-m-Y\ H:i",($row['claim_date_reg']+7200));//поправка к МСК
        if ($user_level==100){
            $claim_nameXML=$claimXML->addChild('claim');
            $claim_nameXML->addAttribute('name', $row['claim_name']);
            $claim_phoneXML=$claimXML->addChild('claim');
            $claim_phoneXML->addAttribute('phone', $row['claim_phone']);
            $claim_descriptionXML=$claimXML->addChild('claim');
            $claim_descriptionXML->addAttribute('description', $row['claim_description']);
            $claim_dateXML=$claimXML->addChild('claim');
            $claim_dateXML->addAttribute('date', $date);
            }
        echo 'Заявка была добавлена:'.$date.'<br>';
        if ($row['claim_id']==$last_id)
            {echo '</b>';}//Снятие выделения
        $claim_image=$row['claim_image'];
        if(!empty($claim_image)){
            if ($row['claim_id']==$last_id)
                {echo '<b>';}
            echo 'Прилагаемое фото:<img src="'.$claim_image.'"><br><br><br>';
            if ($row['claim_id']==$last_id)
                {echo '</b>';}
            }
        else echo '<br><br>';
            if (isset($_POST['show_in_xml']))
                {
                header("Location:toXML.php");
                }
            if (isset($_POST['download_in_xml']))
                {
                header("Location:downloadXML.php");
                }
        }
    if ($user_level==50){
        echo "<a href=feed_form.php>Добавить заявку</a>";
        create_exit();
        }
    else{
        $_SESSION['XML']=$claimsXML->asXML();
        create_exit();
        create_xml_form_view();
        echo '<br>';
        create_xml_form_download();
        }
    }
else
    {
    echo 'Для просмотра и добавления заявок пожалуйста авторизуйтесь<br>';
    echo '<a href=index.php>Форма авторизации</a>';
    }
        
if(isset($_POST['log_out'])){
    unset ($_SESSION['authorized']);
    unset ($_SESSION['user_id']);
    unset ($_SESSION['user_name']);
    unset ($_SESSION['user_level']);
    unset ($_SESSION['claim_name']);
    session_destroy();
    }
function create_exit()
{
    echo '<form method="POST" action="my_claims.php">
    <input type="submit" value="Выйти" name="log_out" />
    </form>';
}
function create_xml_form_view()
{
    echo '<form method="POST" action="my_claims.php">
    <input type="submit" value="Показать в XML" name="show_in_xml" />';
}
function create_xml_form_download()
{
    echo '<form method="POST" action="my_claims.php">
    <input type="submit" value="Загрузить в XML" name="download_in_xml" />';
}
?>
