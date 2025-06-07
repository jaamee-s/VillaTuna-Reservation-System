<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] === 'admin') {
    header("Location: ../index2.php");
    exit();
}

$customerId = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;

if (!$customerId) {
    echo "Customer ID not found in session. Please login again.";
    exit();
}

// Include database connection
include_once("../functions/db.php");

// Fetch last table_id
$result = $conn->query("SELECT table_id FROM tbl_reservation ORDER BY reservation_id DESC LIMIT 1");
$last_table_id = ($result && $result->num_rows > 0) ? intval($result->fetch_assoc()['table_id']) : 0;
$new_table_id = $last_table_id + 1;

// Fetch last staff_id
$result2 = $conn->query("SELECT staff_id FROM tbl_reservation ORDER BY reservation_id DESC LIMIT 1");
$last_staff_id = ($result2 && $result2->num_rows > 0) ? intval($result2->fetch_assoc()['staff_id']) : 0;
$new_staff_id = $last_staff_id + 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Make a Reservation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function confirmReservation() {
      return confirm("Are you sure you want to submit this reservation?");
    }

    function cancelReservation() {
      window.location.href = "dashboard.php"; // Redirect to dashboard or anywhere you want
    }
  </script>
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Make a Reservation</h2>
    <form action="save_reservations.php" method="POST" onsubmit="return confirmReservation();">
      <div class="mb-3">
        <label for="res_date" class="form-label">Reservation Date</label>
        <input type="date" name="reservation_date" id="res_date" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="res_time" class="form-label">Reservation Time</label>
        <input type="time" name="reservation_time" id="res_time" class="form-control" required>
      </div>

      <!-- Hidden fields -->
      <input type="hidden" name="customer_id" value="<?php echo $customerId; ?>">
      <input type="hidden" name="table_id" value="<?php echo $new_table_id; ?>">
      <input type="hidden" name="staff_id" value="<?php echo $new_staff_id; ?>">

      <button type="submit" class="btn btn-primary">Submit Reservation</button>
    </form>

    <!-- Cancel button outside form -->
    <button type="button" class="btn btn-secondary mt-2" onclick="cancelReservation()">Cancel Reservation</button>
  </div>
</body>
</html>
