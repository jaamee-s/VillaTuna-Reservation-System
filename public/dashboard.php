<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] === 'admin') {
  header("Location: ../index2.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Villatuna | Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body {
      background: linear-gradient(to right, rgb(2, 4, 3), rgb(204, 189, 17));
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
    }h2 {
    color: white;
    }p {
    color: white;

    }
  

    
    </style>
  
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Villatuna Dashboard</a>
    <div class="d-flex">
      <a href="../logout.php" class="btn btn-outline-light" onclick="return confirmLogout();">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
  <p class="lead">Here you can manage your reservations and tables.</p>
  

  <div class="row">
    <div class="col-md-6">
      <div class="card bg-primary text-white mb-3">
        <div class="card-body">
          <h5 class="card-title">Reservations</h5>
          <a href="reservations.php" class="btn btn-light">View Reservations</a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card bg-success text-white mb-3">
        <div class="card-body">
          <h5 class="card-title">Tables</h5>
          <a href="tables.php" class="btn btn-light">View Tables</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function confirmLogout() {
  return confirm("Are you sure you want to logout?");
}
</script>

</body>
</html>
