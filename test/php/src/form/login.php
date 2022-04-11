<?php

$login = $_POST['login'] ?? null;
$password = $_POST['password'] ?? null;

if (empty($login) || empty($password)) {
	header('Location: ../index.php');
}

include_once("../class/session.php");
include_once("../class/db.php");

$sql = sprintf("SELECT admin FROM user WHERE login = '%s' AND password = '%s'", $myDb->escape($login), $myDb->escape($password));
if ($result = $myDb->getConnect()->query($sql)) {
	$result = $result->fetch_all(MYSQLI_ASSOC);
	if (!empty($result[0])) {
		$admin = false;
		if (($result[0]['admin'] == true)) {
			$admin = true;
		}
		$session->setValue("isConnected", ['admin' => $admin, 'login' => $login, 'password' => $password]);
	} else {
		$session->setValue("error", "Login ou password incorrect");
	}
} else {
	$session->setValue("error", "Impossible de contacter la db");
}

header('Location: ../index.php');
