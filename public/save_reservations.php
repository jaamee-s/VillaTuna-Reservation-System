<?php
session_start();
include_once("../functions/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values and sanitize
    $reservation_date = trim($_POST['reservation_date']);
    $reservation_time = trim($_POST['reservation_time']);
    $customer_id = intval($_POST['customer_id']);
    $table_id = intval($_POST['table_id']);

    // Use default staff_id (must exist in tbl_staff)
    $staff_id = 1;

    // Validate required fields
    if (empty($reservation_date) || empty($reservation_time)) {
        echo "<script>alert('Reservation date and time are required.'); history.back();</script>";
        exit();
    }

    // Check if the default staff_id exists
    $stmt_check = $conn->prepare("SELECT staff_id FROM tbl_staff WHERE staff_id = ?");
    $stmt_check->bind_param("i", $staff_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows === 0) {
        $stmt_check->close();
        echo "<script>alert('Default staff ID does not exist. Please contact admin.'); history.back();</script>";
        exit();
    }
    $stmt_check->close();

    // Insert reservation with default staff_id
    $stmt = $conn->prepare("INSERT INTO tbl_reservation (reservation_date, reservation_time, customer_id, table_id, staff_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiii", $reservation_date, $reservation_time, $customer_id, $table_id, $staff_id);

    if ($stmt->execute()) {
        echo "<script>alert('Reservation successful!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to save reservation. Please try again.'); history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Direct access not allowed
    header("Location: reservations.php");
    exit();
}
?>
 <style>
    body {
      background: linear-gradient(to right, rgb(2, 4, 3), rgb(204, 189, 17));
      font-family: 'Poppins', sans-serif;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }
    .btn-primary {
      background-color:rgb(204, 189, 17);
      border: none;
    }
    .btn-primary:hover {
      background-color:rgb(204, 189, 17);
    }
    h4 {
      color: rgb(205, 198, 114);
    }
    h2, p {
      color: white;
    }
    label, p {
      color: white;
    }
  </style>
