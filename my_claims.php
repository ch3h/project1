<?php
require_once 'connectDB.php';
require_once 'functions.php';
if (isset($_SESSION['authorized'])) {
    if (isset($_POST['download_in_xml'])) {
        header("location:downloadXML.php");
    }
    if (isset($_POST['show_in_xml'])) {
        header("location:toXML.php");
    }
    $user_id=$_SESSION['user_id'];
    $user_name=$_SESSION['user_name'];
    $user_level=$_SESSION['user_level'];
    if ($user_level==50) {
        echo "$user_name, ваши заявки:<br><br><br>";
        $query="SELECT claim_id, claim_name, claim_phone, claim_description, claim_date_reg, 
            claim_image FROM claims WHERE `user_id`='$user_id'";
        $query_last_id="SELECT max(claim_id) FROM claims WHERE user_id='$user_id'";
    }elseif ($user_level==100) {
            echo '<b>Заявки пользователей:</b><br><br><br>';
            $query="SELECT user_login,user_name, users.user_id,claim_id, claim_name, 
                claim_phone, claim_description, claim_date_reg, claim_image FROM users, 
                claims WHERE claims.user_id=users.user_id ORDER BY claims.claim_id";
            $query_last_id="SELECT max(claim_id) FROM claims";
    }
    if ($result_last=mysqli_query($link,$query_last_id)) {
        $row_last = mysqli_fetch_assoc($result_last);
        $last_id=$row_last['max(claim_id)'];//id последней добавленной заявки пользователя
    }
    $result = mysqli_query($link,$query);
    $rows = mysqli_num_rows($result);
    for ($j=0;$j<$rows;++$j) {
        $row =  mysqli_fetch_assoc($result);
        if ($user_level==100) {
            if ($row['claim_id']==$last_id) {
                echo '<b>'; //Выделение болдом последней добавленной заявки (для админа) 
            }
              echo 'Логин пользователя оставившего заявку:'.$row['user_login'].'<br>';
              echo 'Имя пользователя оставившего заявку:'.$row['user_name'].'<br>';
        }
        if ($user_level==50 && $last_id==$row['claim_id']) {
            echo '<b>';
        }
        echo 'Наименование заявки:'.$row['claim_name'].'<br>';
        echo 'Контактный телефон:'.$row['claim_phone'].'<br>';
        echo 'Описание заявки:'.$row['claim_description'].'<br>';
        $date=date("d-m-Y\ H:i",($row['claim_date_reg']+7200));//поправка к МСК
        echo 'Заявка была добавлена:'.$date.'<br>';
        if ($row['claim_id']==$last_id){
            echo '</b>';//Снятие выделения
        }
        $claim_image=$row['claim_image'];
        if(!empty($claim_image)) {
            if ($row['claim_id']==$last_id) {
                echo '<b>';
            }
            echo 'Прилагаемое фото:<img src="'.$claim_image.'"><br><br><br>';
            if ($row['claim_id']==$last_id) {
                echo '</b>';
            }
        }
        else { 
            echo '<br><br>';
        }
    }
    if ($user_level==50) {
        echo "<a href=feed_form.php>Добавить заявку</a>";
        create_exit();
    }
    else {
        create_exit();
        create_xml_form_view();
        echo '<br>';
        create_xml_form_download();
    }
}
else {
    echo 'Для просмотра и добавления заявок пожалуйста авторизуйтесь<br>';
    echo '<a href=index.php>Форма авторизации</a>';
}
        
if(isset($_POST['log_out']))
{
    unset ($_SESSION['authorized']);
    unset ($_SESSION['user_id']);
    unset ($_SESSION['user_name']);
    unset ($_SESSION['user_level']);
    session_destroy();
}
