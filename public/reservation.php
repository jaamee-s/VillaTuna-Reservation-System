<?php
include_once('../functions/db.php');
include_once('../functions/auth.php');

if (!isAdminLoggedIn()) {
    redirect('../index.php');
}

// Handle status update
if (isset($_POST['update_status'])) {
    $reservation_id = $_POST['reservation_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE Reservations SET status = ? WHERE reservation_id = ?");
    $stmt->bind_param("si", $new_status, $reservation_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Reservation status updated!'); window.location.href='reservations.php';</script>";
}

// Fetch all reservations
$sql = "SELECT r.*, c.full_name, t.table_number 
        FROM Reservations r
        JOIN Customers c ON r.customer_id = c.customer_id
        JOIN RestaurantTables t ON r.table_id = t.table_id
        ORDER BY reservation_date DESC, reservation_time DESC";
$reservations = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Reservations - Villatuna</title>
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<main class="container my-5">
  <h1 class="mb-4">📅 Manage Reservations</h1>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>Customer</th>
          <th>Table</th>
          <th>Date</th>
          <th>Time</th>
          <th>Guests</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $reservations->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['table_number']) ?></td>
            <td><?= $row['reservation_date'] ?></td>
            <td><?= $row['reservation_time'] ?></td>
            <td><?= $row['num_guests'] ?></td>
            <td>
              <span class="badge bg-<?= 
                $row['status'] == 'Booked' ? 'primary' : 
                ($row['status'] == 'Completed' ? 'success' : 'danger') ?>">
                <?= $row['status'] ?>
              </span>
            </td>
            <td>
              <?php if ($row['status'] == 'Booked'): ?>
                <form method="POST" class="d-flex gap-1">
                  <input type="hidden" name="reservation_id" value="<?= $row['reservation_id'] ?>">
                  <select name="status" class="form-select form-select-sm" required>
                    <option value="">Choose</option>
                    <option value="Completed">Complete</option>
                    <option value="Cancelled">Cancel</option>
                  </select>
                  <button type="submit" name="update_status" class="btn btn-sm btn-success">Update</button>
                </form>
              <?php else: ?>
                <span class="text-muted">N/A</span>
              <?php endif; ?>
            </td>
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
