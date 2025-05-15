<?php
// Start session and include DB connection
session_start();
require_once("../functions/db.php");

// Optional: Check if the user is logged in
// if (!isset($_SESSION['customer_id'])) {
//     header("Location: login_form.php");
//     exit();
// }

// Fetch available tables from the database
$sql = "SELECT * FROM RestaurantTables ORDER BY table_number ASC";
$tables = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reserve a Table - Villatuna</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Make a Reservation</h2>

    <form action="../functions/submit_reservation.php" method="POST">
        <label for="full_name">Full Name:</label><br>
        <input type="text" name="full_name" required><br><br>

        <label for="phone">Phone Number:</label><br>
        <input type="text" name="phone" required><br><br>

        <label for="email">Email Address:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="reservation_date">Date:</label><br>
        <input type="date" name="reservation_date" required><br><br>

        <label for="reservation_time">Time:</label><br>
        <input type="time" name="reservation_time" required><br><br>

        <label for="number_of_guests">Number of Guests:</label><br>
        <input type="number" name="number_of_guests" min="1" required><br><br>

        <label for="table_id">Select Table:</label><br>
        <select name="table_id" required>
            <option value="">-- Choose a Table --</option>
            <?php while ($row = $tables->fetch_assoc()): ?>
                <option value="<?= $row['table_id']; ?>">
                    Table <?= $row['table_number']; ?> (Seats: <?= $row['capacity']; ?>, <?= $row['location']; ?>)
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="special_requests">Special Requests:</label><br>
        <textarea name="special_requests" rows="4" cols="40"></textarea><br><br>

        <button type="submit">Submit Reservation</button>
    </form>
</body>
</html>
