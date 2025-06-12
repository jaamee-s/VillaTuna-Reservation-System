<?php
session_start();
include_once("../functions/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header("Location: ../index2.php");
    exit();
}

// Handle status toggle
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $current = $_GET['toggle'] === 'deactivate' ? 'inactive' : 'active';
    $stmt = $conn->prepare("UPDATE tbl_customers SET status = ? WHERE customer_id = ?");
    $stmt->bind_param("si", $current, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_users.php");
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
  <style>
    .badge-active { background-color: green; }
    .badge-inactive { background-color: red; }
  </style>
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
      color: black;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container mt-4">
    <h2 class="mb-4">Manage Users</h2>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <input type="text" class="form-control mb-3" id="searchInput" placeholder="Search by name, username, or email...">

    <table class="table table-bordered" id="usersTable">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Username</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['customer_id']; ?></td>
          <td><?= htmlspecialchars($row['full_name']); ?></td>
          <td><?= $row['username']; ?></td>
        
          <td><?= $row['email']; ?></td>
          <td>
            <span class="badge <?= $row['status'] === 'inactive' ? 'badge-inactive' : 'badge-active' ?>">
              <?= ucfirst($row['status'] ?? 'active'); ?>
            </span>
          </td>
          <td>
            <a href="?toggle=<?= $row['status'] === 'inactive' ? 'activate' : 'deactivate'; ?>&id=<?= $row['customer_id']; ?>" 
               class="btn btn-sm btn-<?= $row['status'] === 'inactive' ? 'success' : 'danger'; ?>"
               onclick="return confirm('Are you sure you want to <?= $row['status'] === 'inactive' ? 'activate' : 'deactivate'; ?> this user?');">
               <?= $row['status'] === 'inactive' ? 'Activate' : 'Deactivate'; ?>
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script>
    const input = document.getElementById('searchInput');
    const rows = document.querySelectorAll('#usersTable tbody tr');

    input.addEventListener('keyup', () => {
      const value = input.value.toLowerCase();
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? '' : 'none';
      });
    });
  </script>
</body>
</html>

<?php $conn->close(); ?>
