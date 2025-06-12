<?php
session_start();
include_once("functions/db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['submit'])) {
  $full_name = trim($_POST['full_name']);
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $status = 'active'; // Optional, but explicit

  $stmt = $conn->prepare("INSERT INTO tbl_customers (full_name, username, email, password, status) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $full_name, $username, $email, $password, $status);

  if ($stmt->execute()) {
    echo "<script>alert('Registration successful!'); window.location.href = 'index2.php';</script>";
  } else {
    echo "<script>alert('Something went wrong.');</script>";
  }

  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Customer Account</title>
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
      background-color: rgb(204, 189, 17);
      border: none;
    }
    .btn-primary:hover {
      background-color: rgb(204, 189, 17);
    }
    h3, label {
      color: white;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h3>Create a New Customer Account</h3>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" class="form-control" name="full_name" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" name="username" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <button type="submit" name="submit" class="btn btn-primary">Register</button>
      <a href="index2.php" class="btn btn-secondary">Back to Login</a>
    </form>
  </div>
</body>
</html>
