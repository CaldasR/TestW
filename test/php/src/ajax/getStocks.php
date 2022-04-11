<?php


include_once("../class/session.php");
include_once("../class/db.php");

$typeSearched = $_GET['type'] ?? null;
$idSearched = $_GET['id'] ?? null;

if ($session->getValue("isConnected") !== null) {

	$sql = "SELECT 
				s.id_provider, s.id_company, s.number,
				COALESCE(provider.name,  company.name) as owner_name,
				product.id, product.name, product.price, product.tax
			FROM `stock` as s
			LEFT JOIN provider ON provider.id = s.id_provider
			LEFT JOIN company ON company.id = s.id_company
			LEFT JOIN product ON product.id = s.id_product " . gestionParams($myDb, $typeSearched, $idSearched);

	$stocks = [];

	if ($result = $myDb->getConnect()->query($sql)) {
	    while ($data = $result->fetch_object()) {
	        $stocks[] = $data;
	    }
	}
	
	echo(json_encode($stocks));
}

function gestionParams($myDb, $typeSearched, $idSearched) {
	$where = ' WHERE 1 = 1 ';

	if ($typeSearched === 'provider' && !is_null($idSearched)) {
		$where .= " AND provider.id = '" . $myDb->escape($idSearched) . "' ";
	}

	if ($typeSearched === 'company' && !is_null($idSearched)) {
		$where .= " AND company.id = '" . $myDb->escape($idSearched) . "' ";
	}

	return $where;
}