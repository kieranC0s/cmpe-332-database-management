<!DOCTYPE html>
<html>
<head>
	<title>View Orders</title>
	<link rel="stylesheet" type="text/css" href="order_style.css">
</head>
<body>
	<header>
		<h1>View Orders</h1>
	</header>

	<nav>
		<ul>
			<li><a href="restaurant.php">Home</a></li>
		</ul>
	</nav>

	<main>
		<h2>Select a Date</h2>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<label for="orderDate">Date (YYYY-MM-DD):</label>
			<input type="text" name="orderDate" id="orderDate" required><br>
			<input type="submit" value="View Orders">
		</form>

		<?php

		include 'connectdb.php';

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$orderDate = $_POST['orderDate'];
            
            // Note to TA: I directly fetch the data in the main php code rather than using a seperate file. I used documentation form the php.net website. 
            // It suggested using prepared statements that use binding to prevent sql injection. I used the bindParam() method to bind the parameter to the variable.
			
            $stmt = $connection->prepare("SELECT customerAccount.firstName, customerAccount.lastName, foodOrder.orderID, foodOrder.totalPrice, foodOrder.tip, foodOrder.orderDate, employee.firstName as deliveryFirstName, employee.lastName as deliveryLastName FROM customerAccount, foodOrder, orderPlacement, delivery, employee WHERE customerAccount.emailAddress = orderPlacement.customerEmail AND foodOrder.orderID = orderPlacement.orderID AND foodOrder.orderDate = :orderDate AND delivery.orderID = orderPlacement.orderID AND employee.ID = delivery.deliveryPerson");
			$stmt->bindParam(':orderDate', $orderDate);
			$stmt->execute();
			$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($orders) {
				echo "<h2>Orders on {$orderDate}</h2>";
				echo "<table border='1'>";
				echo "<tr><th>Customer Name</th><th>Order ID</th><th>Total Price</th><th>Tip</th><th>Delivery Person</th></tr>";
				foreach ($orders as $order) {
					echo "<tr>";
					echo "<td>{$order['firstName']} {$order['lastName']}</td>";
					echo "<td>{$order['orderID']}</td>";
					echo "<td>{$order['totalPrice']}</td>";
					echo "<td>{$order['tip']}</td>";
					echo "<td>{$order['deliveryFirstName']} {$order['deliveryLastName']}</td>";
					echo "</tr>";
				}
				echo "</table>";
			} else {
				echo "No orders found for {$orderDate}.";
			}
		}

		?>

	</main>

	<footer>
		<p>Copyright © 2023 Kieran's Restaurant Database</p>
	</footer>
</body>
</html>