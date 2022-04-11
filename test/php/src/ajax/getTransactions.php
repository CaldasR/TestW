<?php


include_once("../class/session.php");
include_once("../class/db.php");

$idSearched = $_GET['id'] ?? null;
$typeSearched = $_GET['type'] ?? null;

if ($session->getValue("isConnected") !== null) {

	$sql = "SELECT
				product.name as product_name,
			    company.name as company_name,
			    provider.name as provider_name,
			    client.name as client_name,
			    null as employee_name,
			    t.price,
			    t.quantity,
			    t.datetime
			FROM `transaction` as t
			LEFT JOIN provider ON provider.id = t.id_provider
			LEFT JOIN company ON company.id = t.id_company
			LEFT JOIN client ON client.id = t.id_client
			LEFT JOIN product ON product.id = t.id_product "
			.gestionParams($myDb, $typeSearched, $idSearched)
			." ORDER BY t.datetime DESC LIMIT 10";
	$transactions = [];

	if ($result = $myDb->getConnect()->query($sql)) {
	    while ($data = $result->fetch_object()) {
	        $transactions[] = $data;
	    }
	}

	echo(json_encode($transactions));
}

function gestionParams($myDb, $typeSearched, $idSearched) {
	$where = ' WHERE 1 = 1 ';

	if ($typeSearched === 'provider' && !is_null($idSearched)) {
		$where .= " AND provider.id = '" . $myDb->escape($idSearched) . "' ";
	}

	if ($typeSearched === 'company' && !is_null($idSearched)) {
		$where .= " AND company.id = '" . $myDb->escape($idSearched) . "' ";
	}

	if ($typeSearched === 'client' && !is_null($idSearched)) {
		$where .= " AND client.id = '" . $myDb->escape($idSearched) . "' ";
	}

	return $where;
}