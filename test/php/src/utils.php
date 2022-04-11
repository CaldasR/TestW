<?php

function getLogin($session) {
	if (!empty($session->getValue("isConnected")['login'])) {
		return $session->getValue("isConnected")['login'];
	}
	return "";
}

function getAdmin($session) {
	if (!empty($session->getValue("isConnected")['admin'])) {
		return $session->getValue("isConnected")['admin'];
	}
	return false;
}

function addJavascriptVar($javName, $value) {
	echo "<script> var ".$javName." = '".$value."'; </script>";
}