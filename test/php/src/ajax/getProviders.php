<?php


include_once("../class/session.php");
include_once("../class/db.php");

if ($session->getValue("isConnected") !== null) {

	$sql = 'SELECT p.id, p.name, p.address, c.name as country FROM `provider` as p LEFT JOIN country as c on c.iso3 = p.country_iso;';

	$providers = [];

	if ($result = $myDb->getConnect()->query($sql)) {
	    while ($data = $result->fetch_object()) {
	        $providers[] = $data;
	    }
	}

	echo(json_encode($providers));
}