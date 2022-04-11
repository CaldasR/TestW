<?php


include_once("../class/session.php");
include_once("../class/db.php");


$quantityToBuy = $_POST['quantityToBuy'] ?? null;
$selectedProduct = $_POST['selectedProduct'] ?? null;
$dataCompany = $_POST['dataCompany'] ?? null;

if (is_null($quantityToBuy) || is_null($selectedProduct) || is_null($dataCompany) || intval($quantityToBuy) < 1) {
	echo(-1);
	return;
}
$quantityToBuy = intval($quantityToBuy);

$ret = [];
$ret['return'] = -1;
$ret['message'] = 'Impossible de faire cette transaction';

// transaction fournisseur -> compagnie
if (!empty($selectedProduct['id_provider']) || !empty($dataCompany['id']) || !empty($selectedProduct['id'])) {
	// get balance compagnie
	$sql = "SELECT * FROM company WHERE id = '".$myDb->escape($dataCompany['id'])."'";
	$infoCompany = $myDb->getConnect()->query($sql)->fetch_object();
	
	// get stock fournisseur
	$sql = "SELECT * FROM stock WHERE id_provider = '".$myDb->escape($selectedProduct['id_provider'])."' AND id_product = '".$myDb->escape($selectedProduct['id'])."'";
	$stockDispo = $myDb->getConnect()->query($sql)->fetch_object();

	// get info produit
	$sql = "SELECT * FROM product WHERE id = '".$myDb->escape($selectedProduct['id'])."'";
	$infoProduct = $myDb->getConnect()->query($sql)->fetch_object();
	// var_dump($infoProduct);

	// les data sont chargees
	if (!empty($infoCompany) && !empty($stockDispo) && !empty($infoProduct)) {
		$balanceComp = intval($infoCompany->balance);
		$stockFourni = intval($stockDispo->number);
		$priceProduct = intval($infoProduct->price);
		$taxProduct = intval($infoProduct->tax);
		
		if ($stockFourni < $quantityToBuy) {
			$ret['message'] = "Le fournisseur n'a pas assez de stock";
			echo(json_encode($ret));
			return ;
		}

		$priceUnit = ($priceProduct + ($priceProduct * $taxProduct / 100));
		$total = $priceUnit * $quantityToBuy;
		if ($balanceComp < $total) {
			$ret['message'] = "La compagnie n'a pas les fonds pour cette transaction";
			echo(json_encode($ret));
			return ;
		}

		// desactive le commit auto pour pouvoir rollback en cas de probleme
		$myDb->getConnect()->autocommit(FALSE);

		// update stock fourni 
		$sql = "UPDATE `stock` SET `number`='".$myDb->escape($stockFourni - $quantityToBuy)."' WHERE id = '".$myDb->escape($stockDispo->id) . "'";
		$result = $myDb->getConnect()->query($sql);

		if ($result == false) {
			$myDb->getConnect()->rollback();
			echo(json_encode($ret));
			return ;
		}

		// create || update stock compagnie
		$sql = "SELECT * FROM stock WHERE id_company = '".$myDb->escape($infoCompany->id)."' and id_product = '".$myDb->escape($infoProduct->id)."'";
		$stockCompany = $myDb->getConnect()->query($sql)->fetch_object();

		if (empty($stockCompany)) {
			// la company n'a jamais eu de stock de ce produit
			$sql = "INSERT INTO `stock`(`id_company`, `id_product`, `number`) VALUES ('" . $myDb->escape($infoCompany->id) . "','" . $myDb->escape($infoProduct->id) . "','" . $myDb->escape($quantityToBuy) . "')";
			$result = $myDb->getConnect()->query($sql);

			if ($result == false) {
				$myDb->getConnect()->rollback();
				echo(json_encode($ret));
				return ;
			}
		} else {
			$stockActuel = intval($stockCompany->number);
			$sql = "UPDATE `stock` SET `number`='".$myDb->escape($stockActuel + $quantityToBuy)."' WHERE id = '".$myDb->escape($stockCompany->id) . "'";
			$result = $myDb->getConnect()->query($sql);

			if ($result == false) {
				$myDb->getConnect()->rollback();
				echo(json_encode($ret));
				return ;
			}
		}

		// update balance compagnie
		$sql = "UPDATE `company` SET `balance`='".$myDb->escape($balanceComp - $total)."' WHERE id = '".$myDb->escape($infoCompany->id) . "'";
		// echo($sql);
		$result = $myDb->getConnect()->query($sql);
		// var_dump($result);
		 // $myDb->getConnect()->commit();
		if ($result == false) {
			$myDb->getConnect()->rollback();
			echo(json_encode($ret));
			return ;
		}

		// create transaction
		$sql = "INSERT INTO `transaction`(`id_product`, `id_provider`, `id_company`, `price`, `quantity`) VALUES ('".$myDb->escape($infoProduct->id) . "','".$myDb->escape($selectedProduct['id_provider']) . "','".$myDb->escape($infoCompany->id) . "','".$myDb->escape($priceUnit) . "','".$myDb->escape($quantityToBuy) . "')";
			// echo($sql);
		$result = $myDb->getConnect()->query($sql);
		// var_dump($result);
		if ($result == false) {
			$myDb->getConnect()->rollback();
			echo(json_encode($ret));
			return ;
		}

		$myDb->getConnect()->commit();
		// $myDb->getConnect()->rollback();
		$ret['return'] = 1;
		$ret['message'] = $balanceComp - $total;
		echo(json_encode($ret));
		return ;
	}
}

$dataClient = $dataCompany;
if (!empty($selectedProduct['id_company']) || !empty($dataClient['id']) || !empty($selectedProduct['id'])) {
	// get balance client
	$sql = "SELECT * FROM client WHERE id = '".$myDb->escape($dataClient['id'])."'";
	$infoClient = $myDb->getConnect()->query($sql)->fetch_object();

	// get stock company
	$sql = "SELECT * FROM stock WHERE id_company = '".$myDb->escape($selectedProduct['id_company'])."' AND id_product = '".$myDb->escape($selectedProduct['id'])."'";
	$stockDispo = $myDb->getConnect()->query($sql)->fetch_object();

	// get info produit
	$sql = "SELECT * FROM product WHERE id = '".$myDb->escape($selectedProduct['id'])."'";
	$infoProduct = $myDb->getConnect()->query($sql)->fetch_object();

	// les data sont chargees
	if (!empty($infoClient) && !empty($stockDispo) && !empty($infoProduct)) {
		$balanceClient = intval($infoClient->balance);
		$stockFourni = intval($stockDispo->number);
		$priceProduct = intval($infoProduct->price);
		$taxProduct = intval($infoProduct->tax);
		
		if ($stockFourni < $quantityToBuy) {
			$ret['message'] = "La compagnie n'a pas assez de stock";
			echo(json_encode($ret));
			return ;
		}

		$priceUnit = ($priceProduct + ($priceProduct * $taxProduct / 100));
		$total = $priceUnit * $quantityToBuy;
		if ($balanceClient < $total) {
			$ret['message'] = "Le client n'a pas les fonds pour cette transaction";
			echo(json_encode($ret));
			return ;
		}

		// desactive le commit auto pour pouvoir rollback en cas de probleme
		$myDb->getConnect()->autocommit(FALSE);

		// update stock fourni 
		$sql = "UPDATE `stock` SET `number`='".$myDb->escape($stockFourni - $quantityToBuy)."' WHERE id = '".$myDb->escape($stockDispo->id) . "'";
		$result = $myDb->getConnect()->query($sql);

		if ($result == false) {
			$myDb->getConnect()->rollback();
			echo(json_encode($ret));
			return ;
		}
		
		// create || update stock client
		$sql = "SELECT * FROM stock WHERE id_client = '".$myDb->escape($infoClient->id)."' and id_product = '".$myDb->escape($infoProduct->id)."'";
		$stockClient = $myDb->getConnect()->query($sql)->fetch_object();

		if (empty($stockClient)) {
			// la client n'a jamais eu de stock de ce produit
			$sql = "INSERT INTO `stock`(`id_client`, `id_product`, `number`) VALUES ('" . $myDb->escape($infoClient->id) . "','" . $myDb->escape($infoProduct->id) . "','" . $myDb->escape($quantityToBuy) . "')";
			$result = $myDb->getConnect()->query($sql);

			if ($result == false) {
				$myDb->getConnect()->rollback();
				echo(json_encode($ret));
				return ;
			}
		} else {
			$stockActuel = intval($stockClient->number);
			$sql = "UPDATE `stock` SET `number`='".$myDb->escape($stockActuel + $quantityToBuy)."' WHERE id = '".$myDb->escape($stockClient->id) . "'";
			$result = $myDb->getConnect()->query($sql);

			if ($result == false) {
				$myDb->getConnect()->rollback();
				echo(json_encode($ret));
				return ;
			}
		}

		// update balance client
		$sql = "UPDATE `client` SET `balance`='".$myDb->escape($balanceClient - $total)."' WHERE id = '" . $myDb->escape($infoClient->id) . "'";
		$result = $myDb->getConnect()->query($sql);
		
		if ($result == false) {
			$myDb->getConnect()->rollback();
			echo(json_encode($ret));
			return ;
		}

		// create transaction
		$sql = "INSERT INTO `transaction`(`id_product`, `id_company`, `id_client`, `price`, `quantity`) VALUES ('".$myDb->escape($infoProduct->id) . "','".$myDb->escape($selectedProduct['id_company']) . "','".$myDb->escape($infoClient->id) . "','".$myDb->escape($priceUnit) . "','".$myDb->escape($quantityToBuy) . "')";
		$result = $myDb->getConnect()->query($sql);
		
		if ($result == false) {
			$myDb->getConnect()->rollback();
			echo(json_encode($ret));
			return ;
		}

		$myDb->getConnect()->commit();
		// $myDb->getConnect()->rollback();
		$ret['return'] = 1;
		$ret['message'] = $balanceClient - $total;
		echo(json_encode($ret));
		return ;
	}
}