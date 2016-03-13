<?php
$link = new mysqli("localhost", "root", "","test");
mysqli_set_charset($link, "utf8");
/* проверка соединения */
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться:", mysqli_connect_error());
    exit();
}
$query="SELECT user_login,user_name, users.user_id,claim_id, claim_name, 
    claim_phone, claim_description, claim_date_reg, claim_image FROM users, 
    claims WHERE claims.user_id=users.user_id ORDER BY claims.claim_id";
$result = mysqli_query($link,$query);
$rows = mysqli_num_rows($result);
$claimsXML=new SimpleXMLElement("<claims></claims>");
                for ($j=0;$j<$rows;++$j) {
                    $row =  mysqli_fetch_assoc($result);
                    $date=date("d-m-Y\ H:i",($row['claim_date_reg']+7200));
                    $claimXML=$claimsXML->addChild('claim');
                    $claimXML->addAttribute('id',$row['claim_id']);
                    $claim_author_loginXML=$claimXML->addChild('claim');
                    $claim_author_loginXML->addAttribute('author_login', $row['user_login']);
                    $claim_author_nameXML=$claimXML->addChild('claim');
                    $claim_author_nameXML->addAttribute('author_name', $row['user_name']);
                    $claim_nameXML=$claimXML->addChild('claim');
                    $claim_nameXML->addAttribute('name', $row['claim_name']);
                    $claim_phoneXML=$claimXML->addChild('claim');
                    $claim_phoneXML->addAttribute('phone', $row['claim_phone']);
                    $claim_descriptionXML=$claimXML->addChild('claim');
                    $claim_descriptionXML->addAttribute('description', $row['claim_description']);
                    $claim_dateXML=$claimXML->addChild('claim');
                    $claim_dateXML->addAttribute('date', $date);  
                }
header('Content-type: text/xml');
echo $claimsXML->asXML();
