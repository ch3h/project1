<?php
session_start();
$claimsXML=new SimpleXMLElement($_SESSION['XML']);
Header('Content-type: text/xml');
echo $claimsXML->asXML();
unset($_SESSION['XML']);
?>

