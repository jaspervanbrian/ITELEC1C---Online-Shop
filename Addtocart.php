<?php
session_start();

if(isset($_POST["addtocart"])) {
	$choice = $_POST['choice'];
	if(isset($_POST['quantity'])) {
		if(($_SESSION['inventory'][$choice]['stock'] >= $_POST['quantity']) && $_SESSION['inventory'][$choice]['stock'] > 0) {
			if(count($_SESSION['cart'][$choice])) {
				$_SESSION['cart'][$choice]["quantity"] += $_POST['quantity'];
				$_SESSION['inventory'][$choice]['stock'] -= $_POST['quantity'];
			} else {
				$newItem = [
					"letter" => $_SESSION['inventory'][$choice]['letter'],
					"price" => $_SESSION['inventory'][$choice]['price'],
					"quantity" => $_POST['quantity'],
				];
				$_SESSION['cart'][$choice] = $newItem;
				$_SESSION['inventory'][$choice]['stock'] -= $_POST['quantity'];
			}
			header("Location: Form_buy.php");
		} else if ($_SESSION['inventory'][$choice]['stock'] === 0) {
			header("Location: Form_buy.php?error=out");
		} else {
			header("Location: Form_buy.php?error=invalid");
		}
	} else {
		header("Location: Form_buy.php");
	}
}