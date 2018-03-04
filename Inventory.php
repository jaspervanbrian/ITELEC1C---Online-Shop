<?php
	session_start();

	if(!isset($_SESSION['inventory'])) {
		$_SESSION['inventory'] = [
			"Apple" => [
				"stock" => 5,
				"price" => 10,
				"letter" => "A",
				"resupply" => 3,
			],
			"Mango" => [
				"stock" => 2,
				"price" => 28,
				"letter" => "B",
				"resupply" => 1,
			],
			"Orange" => [
				"stock" => 10,
				"price" => 14,
				"letter" => "C",
				"resupply" => 5,
			],
			"Banana" => [
				"stock" => 45,
				"price" => 35,
				"letter" => "D",
				"resupply" => 20,
			],
			"Melon" => [
				"stock" => 10,
				"price" => 70,
				"letter" => "E",
				"resupply" => 5,
			],
			"Watermelon" => [
				"stock" => 90,
				"price" => 100,
				"letter" => "F",
				"resupply" => 45,
			],
			"Chico" => [
				"stock" => 80,
				"price" => 10,
				"letter" => "G",
				"resupply" => 40,
			],
			"Rambutan" => [
				"stock" => 0,
				"price" => 10,
				"letter" => "H",
				"resupply" => 10,
			],
			"Lanzones" => [
				"stock" => 0,
				"price" => 100,
				"letter" => "I",
				"resupply" => 10,
			],
		];
	}

	if(!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Inventory</title>
	<script src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css" />
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<table class="table table-hover table-bordered">
					<thead class="align-middle">
						<tr>
							<th colspan="5" class="text-center">Mr. A's Inventory List</th>
						</tr>
						<tr>
							<th colspan="5" class="text-center">Price Breakdown</th>
						</tr>
					</thead>
					<thead class="thead-inverse">
						<tr>
							<th>Item Name</th>
							<th>Stocks</th>
							<th>Price Per Item</th>
							<th>Total Projected Sales</th>
							<th>Stock Remarks</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($_SESSION['inventory'] as $product => $component) {
						?>
							<tr>
								<td><?= $product ?></td>
								<td><?= $component['stock'] ?></td>
								<td><?= $component['price'] ?>.00</td>
								<td><?= ( $component['stock'] *$component['price'] ) ?>.00</td>
								<td>
									<?php
										if($component['stock'] > $component['resupply']) {
									?>
											<p>Good</p>
									<?php
										} else if ($component['stock'] <= $component['resupply'] && $component['stock'] != 0) {
									?>
											<p>Resupply needed.</p>
									<?php
										} else if ($component['stock'] === 0) {
									?>
											<p>Out of Stock.</p>
									<?php
										}
									?>
								</td>
							</tr>
						<?php
							}
						?>
					</tbody>
				</table>
				<div class="form-group">
					<?php
						if(isset($_GET['from'])) {
					?>
						<a href="Form_buy.php"><button class="btn btn-primary btn-block">Return Back</button></a>
					<?php
						} else {
					?>
						<a href="Submit.php?from=inventory"><button class="btn btn-primary btn-block">Return Back</button></a>
					<?php
						}
					?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>