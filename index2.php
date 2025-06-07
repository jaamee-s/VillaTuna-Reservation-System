<?php
session_start();
include_once("functions/db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Villatuna Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
  </style>
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-6 col-lg-4">
      <div class="text-center mb-4">
        <img src="https://villatuna.com/wp-content/uploads/2023/07/logo-transparent.png" width="380" height="100" alt="Logo">
        <h4 class="mt-2">Table Reservation</h4>
      </div>
      <div class="card p-4">
        <h5 class="text-center mb-3">Login</h5>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="d-grid">
            <button type="submit" name="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
        <div class="text-center mt-3">
          <a href="register_form.php" class="btn btn-outline-dark btn-sm">Create Account</a>
        </div>
      </div>
    </div>
  </div>

<?php
if (isset($_POST["submit"])) {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM tbl_customers WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();

    if ($password === $row['password']) {
      session_regenerate_id(true);
      $_SESSION['user'] = $row['username'];
      $_SESSION['customer_id'] = $row['customer_id']; // ✅ ADD THIS

      if ($row['username'] === 'admin') {
        header("Location: public/admin_dashboard.php");
      } else {
        header("Location: public/dashboard.php");
      }
      exit();
    }
  }

  echo "<script>alert('Invalid username or password');</script>";
  $stmt->close();
}
?>
</body>
</html>
