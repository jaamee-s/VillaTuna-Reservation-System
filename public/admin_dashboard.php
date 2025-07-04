<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
  header("Location: ../index2.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Villatuna | Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Villatuna Admin Dashboard</a>
    <div class="d-flex">
      <a href="../logout.php" class="btn btn-outline-light" onclick="return confirmLogout();">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
  <p class="lead">Manage the restaurant reservations and tables below, with additional admin controls.</p>

  <div class="row">
    <!-- Reservations Card -->
    <div class="col-md-4">
      <div class="card text-white bg-primary mb-3">
        <div class="card-body">
          <h5 class="card-title">Reservations</h5>
          <p class="card-text">View and manage customer reservations.</p>
          <a href="admin_reservations.php" class="btn btn-light">Go</a>
        </div>
      </div>
    </div>

    <!-- Tables Card -->
    <div class="col-md-4">
      <div class="card text-white bg-success mb-3">
        <div class="card-body">
          <h5 class="card-title">Tables</h5>
          <p class="card-text">Check and edit table information.</p>
          <a href="admin_tables.php" class="btn btn-light">Go</a>
        </div>
      </div>
    </div>

    <!-- User Management Card -->
    <div class="col-md-4">
      <div class="card text-white bg-danger mb-3">
        <div class="card-body">
          <h5 class="card-title">User Management</h5>
          <p class="card-text">Manage admin and staff accounts.</p>
          <a href="admin_users.php" class="btn btn-light">Go to Users</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function confirmLogout() {
    return confirm("Are you sure you want to logout?");
  }
</script>

</body>
</html>
