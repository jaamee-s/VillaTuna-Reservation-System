<?php
session_start();
include_once("../functions/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header("Location: ../index2.php");
    exit();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_table_id'])) {
    $id = intval($_POST['update_table_id']);
    $capacity = intval($_POST['capacity']);
    $location = $_POST['location'];

    $stmt = $conn->prepare("UPDATE tbl_restauranttables SET capacity = ?, location = ? WHERE table_id = ?");
    $stmt->bind_param("isi", $capacity, $location, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_tables.php?msg=updated");
    exit();
}

// Handle add new table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_table'])) {
    $table_number = $_POST['table_number'];
    $capacity = intval($_POST['capacity']);
    $location = $_POST['location'];

    $stmt = $conn->prepare("INSERT INTO tbl_restauranttables (table_number, capacity, location) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $table_number, $capacity, $location);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_tables.php?msg=added");
    exit();
}

$result = $conn->query("SELECT * FROM tbl_restauranttables ORDER BY table_number ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Tables</title>
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
    h4, p {
      color: white;
    }
  </style>
<body class="bg-light">
<div class="container mt-4">
  <h2 class="mb-4">Manage Tables</h2>
  <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

  <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
    <div class="alert alert-success">Table updated successfully.</div>
  <?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
    <div class="alert alert-info">New table added.</div>
  <?php endif; ?>

  <h4 class="mb-3">Existing Tables</h4>
  <table class="table table-bordered bg-white">
    <thead class="table-dark">
      <tr>
        <th>Table ID</th>
        <th>Table Number</th>
        <th>Capacity</th>
        <th>Location</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <form method="POST">
          <td><?= $row['table_id']; ?></td>
          <td><?= htmlspecialchars($row['table_number']); ?></td>
          <td>
            <input type="number" name="capacity" value="<?= $row['capacity']; ?>" class="form-control" required>
          </td>
          <td>
            <select name="location" class="form-select" required>
              <option value="Indoor" <?= $row['location'] == 'Indoor' ? 'selected' : '' ?>>Indoor</option>
              <option value="Outdoor" <?= $row['location'] == 'Outdoor' ? 'selected' : '' ?>>Outdoor</option>
            </select>
          </td>
          <td>
            <input type="hidden" name="update_table_id" value="<?= $row['table_id']; ?>">
            <button type="submit" class="btn btn-success btn-sm">Save</button>
          </td>
        </form>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <hr class="my-4">

  <h4>Add New Table</h4>
  <form method="POST" class="row g-3 bg-white p-3 border rounded shadow-sm">
    <input type="hidden" name="add_table" value="1">
    <div class="col-md-4">
      <label for="table_number" class="form-label">Table Number</label>
      <input type="text" name="table_number" id="table_number" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label for="capacity" class="form-label">Capacity</label>
      <input type="number" name="capacity" id="capacity" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label for="location" class="form-label">Location</label>
      <select name="location" id="location" class="form-select" required>
        <option value="Indoor">Indoor</option>
        <option value="Outdoor">Outdoor</option>
      </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button type="submit" class="btn btn-primary w-100">Add Table</button>
    </div>
  </form>
</div>
</body>
</html>

<?php $conn->close(); ?>
