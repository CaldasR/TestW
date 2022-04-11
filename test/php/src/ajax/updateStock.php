<?php


include_once("../class/session.php");
include_once("../class/db.php");

$id_provider = $_POST['id_provider'] ?? null;
$id_product = $_POST['id_product'] ?? null;
$quantity = $_POST['quantity'] ?? null;

if (is_null($id_provider) || is_null($id_product) || is_null($quantity) || intval($quantity) < 1) {
	echo(-1);
	return;
}

// faire une gestion d'erreurs pour les inputs

if ($session->getValue("isConnected") !== null) {

	$sql = "SELECT * FROM stock WHERE id_provider = '".$myDb->escape($id_provider)."' and id_product = '".$myDb->escape($id_product)."'";
	$stocks = [];

	if ($result = $myDb->getConnect()->query($sql)) {
	    while ($data = $result->fetch_object()) {
	        $stocks[] = $data;
	    }
	}

	if (empty($stocks[0])) {
		$total = intval($quantity) ?? 0;
		$sql = "INSERT INTO `stock`(`id_provider`, `id_product`, `number`) VALUES ('" . $myDb->escape($id_provider) . "','" . $myDb->escape($id_product) . "','" . $myDb->escape($total) . "')";
		$result = $myDb->getConnect()->query($sql);
		echo($result);
	} else {
		$idStock = $stocks[0]->id;
		$numberStock = intval($stocks[0]->number);
		$total = $numberStock + intval($quantity);
		$sql = "UPDATE `stock` SET `number`='".$myDb->escape($total)."' WHERE id = '".$myDb->escape($idStock) . "'";
		$result = $myDb->getConnect()->query($sql);
		echo($result);
	}
	return ;
	
}