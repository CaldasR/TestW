<?php

include_once("../class/session.php");
include_once("../class/db.php");

if ($session->getValue("isConnected") !== null) {

	$sql = "SELECT * FROM product";

	$countries = [];

	if ($result = $myDb->getConnect()->query($sql)) {
	    while ($data = $result->fetch_object()) {
	        $countries[] = $data;
	    }
	}

	echo(json_encode($countries));
}