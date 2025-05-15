<?php
include_once('../functions/db.php');
session_start();

if (isset($_POST["submit"])) {
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Check for duplicate email
    $check = $conn->prepare("SELECT * FROM Customers WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO Customers (full_name, phone_number, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $phone_number, $email, $password);
        
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You may now log in.'); window.location.href='../index.php';</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }

        $stmt->close();
    }

    $check->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Villatuna</title>
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
                <h5 class="card-title text-center pb-0 fs-4">Create a Customer Account</h5>
                <p class="text-center small">Fill in the form below to register</p>
              </div>

              <form class="row g-3 needs-validation" method="POST" novalidate>
                <div class="col-12">
                  <label for="full_name" class="form-label">Full Name</label>
                  <input type="text" name="full_name" class="form-control" required>
                  <div class="invalid-feedback">Please enter your full name.</div>
                </div>

                <div class="col-12">
                  <label for="phone" class="form-label">Phone Number</label>
                  <input type="text" name="phone" class="form-control" required>
                  <div class="invalid-feedback">Please enter your phone number.</div>
                </div>

                <div class="col-12">
                  <label for="email" class="form-label">Email Address</label>
                  <input type="email" name="email" class="form-control" required>
                  <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>

                <div class="col-12">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" required>
                  <div class="invalid-feedback">Please enter your password.</div>
                </div>

                <div class="col-12">
                  <button class="btn btn-success w-100" type="submit" name="submit">Register</button>
                </div>
              </form>

              <div class="text-center mt-2">
                <p class="small">Already have an account? <a href="../index.php">Login here</a></p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
</body>
</html>
