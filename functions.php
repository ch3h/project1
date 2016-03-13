<?php
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
    <input type="submit" value="Выйти" name="
    log_out" />
    </form>';
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
