<?php
include_once('../functions/db.php');
include_once('../functions/auth.php');

if (!isAdminLoggedIn()) {
    redirect('../index.php');
}

// Fetch summary stats
$totalCustomers = $conn->query("SELECT COUNT(*) AS total FROM Customers")->fetch_assoc()['total'];
$totalReservations = $conn->query("SELECT COUNT(*) AS total FROM Reservations")->fetch_assoc()['total'];
$totalTables = $conn->query("SELECT COUNT(*) AS total FROM RestaurantTables")->fetch_assoc()['total'];

// Fetch latest 5 reservations
$reservations = $conn->query("
    SELECT r.*, c.full_name, t.table_number 
    FROM Reservations r
    JOIN Customers c ON r.customer_id = c.customer_id
    JOIN RestaurantTables t ON r.table_id = t.table_id
    ORDER BY reservation_date DESC, reservation_time DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Villatuna</title>
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
  <main class="container my-5">
    <h1 class="mb-4">🍽️ Villatuna Admin Dashboard</h1>

    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card text-bg-primary mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Customers</h5>
            <p class="card-text fs-2"><?= $totalCustomers ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-bg-success mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Reservations</h5>
            <p class="card-text fs-2"><?= $totalReservations ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-bg-warning mb-3">
          <div class="card-body">
            <h5 class="card-title">Total Tables</h5>
            <p class="card-text fs-2"><?= $totalTables ?></p>
          </div>
        </div>
      </div>
    </div>

    <h3 class="mb-3">📋 Latest Reservations</h3>
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Customer</th>
            <th>Table</th>
            <th>Date</th>
            <th>Time</th>
            <th>Guests</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $reservations->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['full_name']) ?></td>
              <td><?= htmlspecialchars($row['table_number']) ?></td>
              <td><?= htmlspecialchars($row['reservation_date']) ?></td>
              <td><?= htmlspecialchars($row['reservation_time']) ?></td>
              <td><?= htmlspecialchars($row['num_guests']) ?></td>
              <td><span class="badge bg-<?= $row['status'] === 'Booked' ? 'success' : ($row['status'] === 'Cancelled' ? 'danger' : 'secondary') ?>">
                <?= $row['status'] ?>
              </span></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </main>

  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
