<?php
echo 'vehiculo seleccionado', '<br />';
$todo = array();
while($fila = $result->fetch_assoc()) {
	$todo[] = $fila;
}

var_dump($todo);
?>