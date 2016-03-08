<meta charset="UTF-8">
<?php
session_start();
$_SESSION['file_route']=$file_route=NULL;//Добавится в БД если пользователь не загрузил изображение
if (isset($_SESSION['authorized']))
    {
    create_form();    
    if(isset($_POST['send'])){
        $claim_name=$_POST['claim_name'];
        $claim_phone=$_POST['claim_phone'];
        $claim_description=$_POST['claim_description'];
        if ($claim_name!= "" && $claim_phone!= ""&& $claim_description != "")
            { 
            if (preg_match("/[0-9]+$/",$claim_phone)){
                if(strlen($claim_description)>=10){
                    $_SESSION['claim_name']=$claim_name;
                    $_SESSION['claim_phone']=$claim_phone;
                    $_SESSION['claim_description']=$claim_description;
                    if($_FILES["filename"]["size"]!=NULL)
                        {
                        if($_FILES["filename"]["size"] > 1024*5*1024)
                            {
                            require_once 'connectDB.php';
                                 echo ("Размерs файла превышает 5 мегабайт");
                                 exit;
                            }
                                $imageinfo = getimagesize($_FILES['filename']['tmp_name']);
                                if($imageinfo['mime'] != 'image/png' && $imageinfo['mime'] != 'image/jpeg')
                                    {
                                    echo "Только фото в формате jpeg или png";
                                    exit;
                                    }
                                // Проверяем загружен ли файл
                                if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
                                    {
                                  // Если файл загружен успешно, перемещаем его
                                  // из временной директории в конечную
                                  $temp = explode(".", $_FILES["filename"]["name"]);
                                  $newfilename = round(microtime(true)) . '.' . end($temp);
                                  move_uploaded_file($_FILES["filename"]["tmp_name"], "./files/" . $newfilename);
                                  $_SESSION['file_route']=$file_route="files/".$newfilename;
                                    }
                                else {
                                    echo("Ошибка загрузки файла");
                                    }
                            require_once 'connectDB.php';
                            }
                        else 
                            {
                            require_once 'connectDB.php';
                            }
                    if ($insert_claims_query->execute())
                        { 
                        header("Location:my_claims.php"); 
                        }
                    else {echo trigger_error($link->error."[$insert_claims_query]");}
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
//    if (empty($_SESSION['authorized']))
    else
        {
        echo 'Вам необходимо авторизоваться<br>';
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

function create_form()
    {
    echo '<form method="POST" action="feed_form.php" enctype="multipart/form-data" >
    <p><strong>*Название заявки:</strong></p>
    <p><input type="text" name="claim_name" placeholder="Название заявки"> </p>
    <p><strong>*Контактный телефон:</strong></p>
    <p><input type="tel" name="claim_phone" placeholder="Номер телефона"></p>
    <p><strong>*Описание проблемы:</strong></p> 
    <p><textarea name="claim_description" placeholder="Опишите вашу проблему" cols="70" rows="15"></textarea></p>
    <p><strong>Загрузить изображение</strong></p> 
    <p><input type = "file" name="filename"/></p>
    <p>* Отмечены обязательные поля</p> 
    <input type="submit" value="Отправить заявку" name="send" />
    </form>
    <form method="POST" action="feed_form.php">
    <input type="submit" value="Выйти" name="log_out" />
    </form>';
    }   
?>
