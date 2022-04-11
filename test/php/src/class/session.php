<?php

session_start(); 

Class Session { 

    function getValue($name, $del = false) {
    	$ret = $_SESSION[$name] ?? null;
    	if ($del) unset($_SESSION[$name]);
    	return $ret;
    }

    function setValue($name, $value) {
		$_SESSION[$name] = $value;    	
    }

    function delValue($name) {
    	unset($_SESSION[$name]); 
    }
}

$session = new Session; 

// $session->delValue("isConnected");

// var_dump($foo->getValue("isConnected"));
// $foo->setValue("isConnected", "cool");
// var_dump($foo->getValue("isConnected"));

// if ($session->getValue("isConnected") === null) {
// 	// var_dump("GO LOG");
// 	include("./login.php");
// }