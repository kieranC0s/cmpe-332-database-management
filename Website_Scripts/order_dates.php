<!DOCTYPE html>
<html>
<head>
	<title>Order Dates</title>
	<link rel="stylesheet" type="text/css" href="order_style.css">
</head>
<body>
	<header>
		<h1>Order Dates</h1>
	</header>

	<nav>
		<ul>
			<li><a href="restaurant.php">Home</a></li>
		</ul>
	</nav>

	<main>
		<h2>View Order Count by Date</h2>

		<?php

		include 'connectdb.php';

		$stmt = $connection->prepare("SELECT orderDate, COUNT(orderID) as orderCount FROM foodOrder GROUP BY orderDate");
		$stmt->execute();
		$orderCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if ($orderCounts) {
			echo "<table border='1'>";
			echo "<tr><th>Date</th><th>Number of Orders</th></tr>";
			foreach ($orderCounts as $orderCount) {
				echo "<tr>";
				echo "<td>{$orderCount['orderDate']}</td>";
				echo "<td>{$orderCount['orderCount']}</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "No orders found.";
		}

		?>
	</main>

	<footer>
		<p>Copyright © 2023 Kieran's Restaurant Database</p>
	</footer>
</body>
</html>