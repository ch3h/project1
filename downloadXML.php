<?php
session_start();
$claimsXML=new SimpleXMLElement($_SESSION['XML']);
header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="claims.xml"');
echo $claimsXML->asXML();
exit();
?>