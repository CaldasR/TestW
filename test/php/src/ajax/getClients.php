<?php


include_once("../class/session.php");
include_once("../class/db.php");

if ($session->getValue("isConnected") !== null) {

	$sql = 'SELECT p.id, p.name, p.address, p.balance, c.name as country FROM `client` as p LEFT JOIN country as c on c.iso3 = p.country_iso;';

	$clients = [];

	if ($result = $myDb->getConnect()->query($sql)) {
	    while ($data = $result->fetch_object()) {
	        $clients[] = $data;
	    }
	}

	echo(json_encode($clients));
}