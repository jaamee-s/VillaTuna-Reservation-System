<?php
session_start();
include_once("../functions/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header("Location: ../index2.php");
    exit();
}

// Fetch reservations with customer and table info
$sql = "SELECT r.*, c.full_name, t.table_number 
        FROM tbl_reservation r
        JOIN tbl_customers c ON r.customer_id = c.customer_id
        JOIN tbl_restauranttables t ON r.table_id = t.table_id
        ORDER BY r.reservation_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Reservations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
  <style>
    body {
      background: linear-gradient(to right, rgb(152, 164, 158), rgba(27, 26, 18, 0.9));
      font-family: 'Poppins', sans-serif;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }
    .btn-primary {
      background-color: #009688;
      border: none;
    }
    .btn-primary:hover {
      background-color: #00796b;
    }
    h2, p {
      color: white;
    }
  </style>
<body class="bg-light">
  <div class="container mt-4">
    <h2 class="mb-4">All Reservations</h2>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
    
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'cancel_success'): ?>
  <div class="alert alert-warning">Reservation canceled.</div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'undo_success'): ?>
  <div class="alert alert-success">Reservation restored.</div>
<?php endif; ?>


    <?php if ($result->num_rows > 0): ?>
      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Table #</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
<?php while ($row = $result->fetch_assoc()): ?>
  <?php $status = strtolower($row['status'] ?? 'booked'); ?>
  <tr>
    <td><?= $row['reservation_id']; ?></td>
    <td><?= htmlspecialchars($row['full_name']); ?></td>
    <td><?= $row['table_number']; ?></td>
    <td><?= $row['reservation_date']; ?></td>
    <td><?= $row['reservation_time']; ?></td>
    <td><?= ucfirst($status); ?></td>
    <td class="d-flex gap-2">
      <?php if ($status === 'canceled'): ?>
        <a href="undo_reservation.php?id=<?= $row['reservation_id']; ?>" class="btn btn-warning btn-sm">Undo</a>
        <button class="btn btn-danger btn-sm" disabled>Cancel</button>
      <?php else: ?>
        <button class="btn btn-warning btn-sm" disabled>Undo</button>
        <a href="cancel_reservation.php?id=<?= $row['reservation_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this reservation?');">Cancel</a>
      <?php endif; ?>
    </td>
  </tr>
<?php endwhile; ?>

</tbody>

      </table>
    <?php else: ?>
      <p>No reservations found.</p>
    <?php endif; ?>
  </div>
</body>
</html>
<?php $conn->close(); ?>
