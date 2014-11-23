<?php
require("databaseConfig.inc");
$bdDriver = new mysqli($host, $user, $pass, $bd);
$result = $bdDriver->query("SELECT idUser,user,name,paternalLastname,maternalLastname,email,job,telephone,status FROM User ");
$users = array();
while($row = $result->fetch_assoc()) {
	$users[] = $row;	
}
echo json_encode($users);
?>