<?php
	session_start();

	if (isset($_GET['checkout'])) {
		if ($_GET['checkout'] === "success") {
			unset($_SESSION['cart']);
		}
	}
	if (isset($_POST['endSession'])) {
		if ($_POST['endSession'] === "endSession") {
			session_destroy();
			$_SESSION = array();
			header("Location: Form_buy.php");
		}
	}
	if (!isset($_SESSION['inventory'])) {
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
	<title>Form Buy</title>
	<script src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css" />
		<style>
		.header {
			text-align: center;
		}
		.inline {
			display: inline;
		}
		.border {
			border-left: 1px solid black;
		}
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
			/* display: none; <- Crashes Chrome on hover */
			-webkit-appearance: none;
			margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
		}
	</style>
	<script>
		<?php 
			if(isset($_GET['error'])) {
				if($_GET['error'] === "out") {
		?>
					alert("The stock you ordered is out of stock.");
		<?php
				} else if ($_GET['error'] === "invalid") {
		?>
					alert("Order rejected. Store doesn't have much stock for your desired quantity.");
		<?php
				}
			}
		?>
		$(document).ready(function() {
			var products = <?= json_encode($_SESSION['inventory']); ?>;

			var cart = <?= json_encode($_SESSION['cart']); ?>;

			var check = function(cart) {
				if ($.isEmptyObject(cart)) {
					$("#submitForm").hide();
				} else {
					$("#submitForm").show();
				}
			}

			$("#addtocart").on('click', function() {
				$form = $(this).closest('form').attr("action", "Addtocart.php");
				$form.submit();
			});

			$("#submitForm").on('click', function() {
				$form = $(this).closest('form').attr("action", "Submit.php");
				$form.submit();
			});

			$("#choice").on('click', function() {
				if($.trim($("#choice").val()) != "") {
					if($.trim($("#quantity").val()) === "") {
						if(parseInt(products[$("#choice").val()].stock) === 0) {
							$("#choiceText").html($("#choice").val() + ' <strong style="color: red">(OUT OF STOCK)</strong>');
							$("#totalBill").text("");
							$("#addtocart").hide();
						} else {
							check(cart);
							$("#totalBill").text("");
							$("#choiceText").text($("#choice").val());
							$("#addtocart").hide();
						}
					} else {
						if(parseInt(products[$("#choice").val()].stock) === 0) {
							$("#choiceText").html($("#choice").val() + ' <strong style="color: red">(OUT OF STOCK)</strong>');
							$("#totalBill").text("");
							$("#addtocart").hide();
							check(cart);
						} else {
							$("#addtocart").show();
							$("#submitForm").show();
							$("#totalBill").text("Total bill: " + (parseInt($("#quantity").val()) * products[$("#choice").val()].price));
							$("#choiceText").text($("#choice").val());
						}
					}
				} else {
					check(cart);
					$("#addtocart").hide();
					$("#totalBill").text("");
					$("#choiceText").text("");
					$("#choiceQty").text("");
				}
			});

			$("#quantity").on('keyup', function() {
				if($.trim($("#choice").val()) != "") {
					if ($.trim($("#quantity").val()) === "") {
						$("#choiceQty").text("");
						$("#totalBill").text("");
						$("#addtocart").hide();
						check(cart);
					} else if ($("#quantity").val() <= 0) {
						$("#quantity").val("");
						$("#choiceQty").text("");
						$("#totalBill").text("");
						$("#addtocart").hide();
						check(cart);
					} else {
						if(parseInt(products[$("#choice").val()].stock) === 0) {
							$("#choiceText").html($("#choice").val() + ' <strong style="color: red">(OUT OF STOCK)</strong>');
							$("#totalBill").text("");
							$("#addtocart").hide();
							check(cart);
						} else {
							$("#choiceQty").text(" (" + $("#quantity").val() + " pcs)");
							$("#totalBill").text("Total bill: " + (parseInt($("#quantity").val()) * products[$("#choice").val()].price));
							$("#addtocart").show();
							$("#submitForm").show();
						}
					}
				} else {
					$("#choiceQty").text("");
					$("#addtocart").hide();
					check(cart);
				}
			});
			if(parseInt(products[$("#choice").val()].stock) === 0) {
				$("#choiceText").html($("#choice").val() + ' <strong style="color: red">(OUT OF STOCK)</strong>');
			} else {
				$("#choiceText").text($("#choice").val());
			}

			$("#addtocart").hide();
			check(cart);
		});
	</script>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-3">
				<hr>
				<h3 class="header">FRUITS MENU</h3>
				<hr>
				<?php
					foreach($_SESSION['inventory'] as $product => $components) {
				?>
						<p><?= $components["letter"] ?>. <?= $product ?></p>
				<?php
					}
				?>
			</div>
			<div class="col-4 border" style="padding-top: 20px;">
				<form action="" method="POST" id="formbuy">
					<div class="form-group">
						<p>Choice: </p>
						<select name="choice" id="choice" class="form-control" required>
							<?php
								foreach($_SESSION['inventory'] as $prod => $comp) {
							?>
									<option value="<?= $prod ?>"><?= $comp["letter"] ?>. <?= $prod ?></option>
							<?php
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<p>Quantity: </p>
						<input type="number" name="quantity" id="quantity" class="form-control" min="1">
					</div>
					<div class="jumbotron">
						<p>Item to purchase: <span id="choiceText"></span> <span id="choiceQty"></span></p>
						<p><span id="totalBill"></span></p>
					</div>
					<div class="form-group" id="subButtons">
						<button class="btn btn-primary" id="addtocart" name="addtocart" value="addtocart" type="submit">Add to Cart</button>
						<button class="btn btn-primary" id="submitForm" name="submitForm" value="submitForm" type="submit">Submit</button>
					</div>
				</form>
			</div>
			<?php 
				if(count($_SESSION['cart'])) {
			?>
				<div class="col-4 border">
					<hr>
					<h3>Add Cart List</h3>
					<hr>
					<?php
						$totalCartPrice = 0;
						foreach($_SESSION['cart'] as $key => $value) {
							$totalCartPrice += $value['quantity']*$value['price'];
					?>
							<p><?= $value['letter'] ?>. <?= $key ?> -- <?= $value['quantity'] ?>pcs -- PHP <?= ($value['quantity']*$value['price']) ?>.00</p>
					<?php
						}
					?>
					<hr>
					<p>Grand Total: PHP <?= $totalCartPrice ?>.00</p>
					<hr>
					<div class="form-group">
						<a href="Inventory.php?from=formBuy"><button class="btn btn-primary">Show Inventory</button></a>
					</div>
				</div>
			<?php
				}
			?>
		</div>
	</div>
</body>
</html>