<?php


include_once("../class/session.php");
include_once("../class/db.php");

$id = $_POST['id'] ?? null;
$name = $_POST['name'] ?? null;
$price = intval($_POST['price']) ?? null;
$tax = intval($_POST['tax']) ?? null;

if (is_null($id) || is_null($name) || is_null($price) || is_null($tax)) {
	echo(-1);
	return;
}

// faire une gestion d'erreurs pour les inputs

if ($session->getValue("isConnected") !== null) {

	// Il serait interessant d'ajouter une colonne desactive(datetime) et insert une nouvelle row pour avoir un historique

	$sql = "UPDATE `product` SET 
				`name`='".$myDb->escape($name)."',
				`price`='".$myDb->escape($price)."',
				`tax`='".$myDb->escape($tax)."'
			WHERE id = ".$myDb->escape($id);

	$result = $myDb->getConnect()->query($sql);
	echo($result);
}