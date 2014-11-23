<?php
$id = $_POST['id'];
//$id = '1';
require("databaseConfig.inc");
$bdDriver = new mysqli($host, $user, $pass, $bd);
$result = $bdDriver->query("SELECT idUser,user,name,paternalLastname,maternalLastname,email,job,telephone,status FROM User WHERE idUser=$id");
$row = $result->fetch_assoc();
echo json_encode($row);
?>