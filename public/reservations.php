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

include_once("../functions/db.php");

// Get selected table_id from POST
$table_id = isset($_POST['table_id']) ? intval($_POST['table_id']) : 0;

// Validate selected table exists
$stmt = $conn->prepare("SELECT table_id FROM tbl_restauranttables WHERE table_id = ?");
$stmt->bind_param("i", $table_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "<script>alert('The selected table does not exist.'); window.location.href='choose_table.php';</script>";
    exit();
}
$stmt->close();
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
      window.location.href = "dashboard.php";
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
      <input type="hidden" name="table_id" value="<?php echo $table_id; ?>">

      <button type="submit" class="btn btn-primary">Submit Reservation</button>
    </form>

    <button type="button" class="btn btn-secondary mt-2" onclick="cancelReservation()">Cancel Reservation</button>
  </div>
</body>
</html>
