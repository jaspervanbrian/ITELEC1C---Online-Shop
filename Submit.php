<?php
	session_start();
	
	if(isset($_POST['submitForm'])) {
		if(isset($_POST['quantity']) && isset($_POST['choice'])) {
			if ($_POST['quantity'] > 0) {
				$choice = $_POST['choice'];
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
				} else if($_SESSION['inventory'][$choice]['stock'] === 0){
					header("Location: Form_buy.php?error=out");
				} else {
					header("Location: Form_buy.php?error=invalid");
				}
			} else {
				if (!count($_SESSION['cart'])) {
					header("Location: Form_buy.php");
				}
			}
		} else {
			if (!count($_SESSION['cart'])) {
				header("Location: Form_buy.php");
			}
		}
	} else if (isset($_GET['from'])) {

	} else {
		header("Location: Form_buy.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Submit</title>
	<script src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css" />
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="jumbotron">
					<h2>Congratulations!</h2>
					<p>Items bought:</p>
					<?php
						$totalCartPrice = 0;
						foreach($_SESSION['cart'] as $key => $value) {
							$totalCartPrice += $value['quantity']*$value['price'];
					?>
							<p><?= $value['letter'] ?>. <?= $key ?> -- <?= $value['quantity'] ?>pcs @ PHP <?= ($value['quantity']*$value['price']) ?>.00</p>
					<?php
						}
					?>
					<hr>
					<p>Grand Total: PHP <?= $totalCartPrice ?>.00</p>
					<div class="btn-group btn-group-lg" role="group" aria-label="Basic example">
						<a href="Form_buy.php?checkout=success"><button type="button" class="btn btn-primary">Buy Again?</button></a>
						<a href="Inventory.php"><button type="button" class="btn btn-info">Show Inventory</button></a>
						<form action="Form_buy.php" method="POST">
							<button type="submit" class="btn btn-secondary" id="endSession" name="endSession" value="endSession">Exit</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>