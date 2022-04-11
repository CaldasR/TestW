<?php


include_once("../class/session.php");
include_once("../class/db.php");

$id = $_POST['id'] ?? null;
$name = $_POST['name'] ?? null;
$balance = intval($_POST['balance']) ?? null;
$countryIso = $_POST['countryIso'] ?? null;

if (is_null($id) || is_null($name) || is_null($balance) || is_null($countryIso)) {
	echo(-1);
	return;
}

// faire une gestion d'erreurs pour les inputs

if ($session->getValue("isConnected") !== null) {

	// Il serait interessant d'ajouter une colonne desactive(datetime) et insert une nouvelle row pour avoir un historique

	$sql = "UPDATE `company` SET 
				`name`='".$myDb->escape($name)."',
				`balance`='".$myDb->escape($balance)."',
				`country_iso`='".$myDb->escape($countryIso)."'
			WHERE id = ".$myDb->escape($id);

	$result = $myDb->getConnect()->query($sql);
	echo($result);
}