<?php
include_once('../functions/db.php');
session_start();

if (isset($_POST["submit"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Get customer by email
    $stmt = $conn->prepare("SELECT * FROM Customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['customer_id'] = $row['customer_id'];
            $_SESSION['customer_name'] = $row['full_name'];
            echo "<script>alert('Welcome, " . $row['full_name'] . "!'); window.location.href='../public/reservations.php';</script>";
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('No account found with that email');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Login - Villatuna</title>
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="col-lg-5 col-md-8 d-flex flex-column align-items-center justify-content-center">
          <div class="card mb-3">
            <div class="card-body">
              <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Customer Login</h5>
                <p class="text-center small">Enter your credentials to access your reservations</p>
              </div>

              <form class="row g-3 needs-validation" method="POST" novalidate>
                <div class="col-12">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" required>
                  <div class="invalid-feedback">Please enter your email address.</div>
                </div>

                <div class="col-12">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" required>
                  <div class="invalid-feedback">Please enter your password.</div>
                </div>

                <div class="col-12">
                  <button class="btn btn-primary w-100" type="submit" name="submit">Login</button>
                </div>
              </form>

              <div class="text-center mt-2">
                <p class="small">Don't have an account yet? <a href="register_form.php">Register here</a></p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
</body>
</html>
