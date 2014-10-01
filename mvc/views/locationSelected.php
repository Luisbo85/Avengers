<?php
echo 'ubicacion seleccionada';
$todo = array();
while($fila = $result->fetch_assoc()) {
	$todo[] = $fila;
}

var_dump($todo);
?>