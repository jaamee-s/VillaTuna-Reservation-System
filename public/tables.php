<?php
include_once('../functions/db.php');
include_once('../functions/auth.php');

if (!isAdminLoggedIn()) {
    redirect('../index.php');
}

// Fetch all tables
$sql = "SELECT * FROM RestaurantTables ORDER BY table_number ASC";
$tables = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Restaurant Tables - Villatuna</title>
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<main class="container my-5">
  <h1 class="mb-4">🍽️ Restaurant Tables Information</h1>

  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Table Number</th>
          <th>Seating Capacity</th>
          <th>Location</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $tables->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['table_number']) ?></td>
            <td><?= htmlspecialchars($row['seating_capacity']) ?></td>
            <td><?= htmlspecialchars(ucfirst($row['location'])) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <a href="dashboard.php" class="btn btn-secondary mt-4">← Back to Dashboard</a>
</main>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
