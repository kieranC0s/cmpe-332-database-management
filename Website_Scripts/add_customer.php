<!DOCTYPE html>
<html>
<head>
	<title>Add a New Customer</title>
	<link rel="stylesheet" type="text/css" href="style_AC.css">
</head>
<body>
	<header>
		<h1>Add a New Customer</h1>
	</header>

	<nav>
		<ul>
			<li><a href="restaurant.php">Home</a></li>
		</ul>
	</nav>

	<main>
		<h2>Add Your Information</h2>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<label for="email">Email Address:</label>
			<input type="email" name="email" id="email" required><br>

			<label for="fname">First Name:</label>
			<input type="text" name="fname" id="fname" required><br>

			<label for="lname">Last Name:</label>
			<input type="text" name="lname" id="lname" required><br>

			<label for="cellNum">Cell Number:</label>
			<input type="text" name="cellNum" id="cellNum" required><br>

			<label for="streetAddress">Street Address:</label>
			<input type="text" name="streetAddress" id="streetAddress" required><br>

			<label for="city">City:</label>
			<input type="text" name="city" id="city" required><br>

			<label for="postalCode">Postal Code:</label>
			<input type="text" name="postalCode" id="postalCode" required><br>

			<input type="submit" value="Add Customer">
		</form>

		<?php

		include 'connectdb.php';

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$email = $_POST['email'];
			$fname = $_POST['fname'];
			$lname = $_POST['lname'];
			$cellNum = $_POST['cellNum'];
			$streetAddress = $_POST['streetAddress'];
			$city = $_POST['city'];
			$postalCode = $_POST['postalCode'];

			$stmt = $connection->prepare("SELECT * FROM customerAccount WHERE emailAddress = :email");
			$stmt->bindParam(':email', $email);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if (!$result) {
				$creditAmt = 5.00;
				$stmt = $connection->prepare("INSERT INTO customerAccount (emailAddress, firstName, lastName, cellNum, streetAddress, city, pc, creditAmt) VALUES (:email, :fname, :lname, :cellNum, :streetAddress, :city, :postalCode, :creditAmt)");
				$stmt->bindParam(':email', $email);
				$stmt->bindParam(':fname', $fname);
				$stmt->bindParam(':lname', $lname);
				$stmt->bindParam(':cellNum', $cellNum);
				$stmt->bindParam(':streetAddress', $streetAddress);
				$stmt->bindParam(':city', $city);
				$stmt->bindParam(':postalCode', $postalCode);
				$stmt->bindParam(':creditAmt', $creditAmt);
				$stmt->execute();
				echo "New customer added successfully with a $5.00 credit.";
			} else {
				echo "This email address already exists in the database.";
			}
		}

		?>
	</main>

	<footer>
		<p>Copyright © 2023 Kieran's Restaurant Database</p>
	</footer>
</body>
</html>