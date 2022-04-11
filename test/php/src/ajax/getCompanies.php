<?php


include_once("../class/session.php");
include_once("../class/db.php");

if ($session->getValue("isConnected") !== null) {

	$sql = 'SELECT c.id, c.name, c.balance, co.name as country FROM `company` as c LEFT JOIN country as co on co.iso3 = c.country_iso;';

	$companies = [];

	if ($result = $myDb->getConnect()->query($sql)) {
	    while ($data = $result->fetch_object()) {
	        $companies[] = $data;
	    }
	}

	echo(json_encode($companies));
}