<!DOCTYPE html>
<html>
<head>
	<title>Employee Schedule</title>
	<link rel="stylesheet" type="text/css" href="order_style.css">
</head>
<body>
	<header>
		<h1>Employee Schedule</h1>
	</header>

	<nav>
		<ul>
			<li><a href="restaurant.php">Home</a></li>
		</ul>
	</nav>

	<main>
		<h2>View Employee Schedule</h2>
		
		<!-- I make sure the the same script is handling form display and form processing. -->
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<label for="employee">Select an Employee:</label>
			<select name="employee" id="employee">
				<?php
				include 'connectdb.php';

				$stmt = $connection->prepare("SELECT ID, firstName, lastName FROM employee");
				$stmt->execute();
				$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($employees as $employee) {
					echo "<option value=\"{$employee['ID']}\">{$employee['firstName']} {$employee['lastName']}</option>";
				}
				?>
			</select><br>
			<input type="submit" value="View Schedule">
		</form>

		<?php

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$employeeID = $_POST['employee'];

			$stmt = $connection->prepare("SELECT firstName, lastName FROM employee WHERE ID = :employeeID");
			$stmt->bindParam(':employeeID', $employeeID);
			$stmt->execute();
			$employee = $stmt->fetch(PDO::FETCH_ASSOC);

			$stmt = $connection->prepare("SELECT theday, startTime, endTime FROM shift WHERE empID = :employeeID AND theday IN ('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday') ORDER BY FIELD(theday, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')");
			$stmt->bindParam(':employeeID', $employeeID);
			$stmt->execute();
			$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if ($schedules) {
				echo "<h2>Schedule for {$employee['firstName']} {$employee['lastName']}</h2>";
				echo "<table border='1'>";
				echo "<tr><th>Day</th><th>Start Time</th><th>End Time</th></tr>";
				foreach ($schedules as $schedule) {
					echo "<tr>";
					echo "<td>{$schedule['theday']}</td>";
					echo "<td>{$schedule['startTime']}</td>";
					echo "<td>{$schedule['endTime']}</td>";
					echo "</tr>";
				}
				echo "</table>";
			} else {
				echo "No schedule found for {$employee['firstName']} {$employee['lastName']}.";
			}
		}

		?>
	</main>

	<footer>
		<p>Copyright © 2023 Kieran's Restaurant Database</p>
	</footer>
</body>
</html>