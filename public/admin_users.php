<?php
session_start();
include_once("../functions/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header("Location: ../index2.php");
    exit();
}

$result = $conn->query("SELECT * FROM tbl_customers ORDER BY full_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - User Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4">
    <h2 class="mb-4">Manage Users</h2>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Customer ID</th>
          <th>Full Name</th>
          <th>Username</th>
          <th>Phone</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['customer_id']; ?></td>
          <td><?= htmlspecialchars($row['full_name']); ?></td>
          <td><?= $row['username']; ?></td>
          <td><?= $row['phone_number']; ?></td>
          <td><?= $row['email']; ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
<?php $conn->close(); ?>
