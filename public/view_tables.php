<?php
session_start();
include_once("../functions/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user'] === 'admin') {
    header("Location: ../index2.php");
    exit();
}

// Fetch all tables
$sql = "SELECT * FROM tbl_restauranttables ORDER BY table_number ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Tables</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
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
    h2, p {
      color: white;
    }
    label, p {
      color: white;
    }
  </style>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Available Tables</h2>
    <?php if ($result->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="table-dark">
            <tr>
              <th>Table Number</th>
              <th>Capacity</th>
              <th>Location</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['table_number']); ?></td>
                <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                <td><?php echo htmlspecialchars($row['location']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p>No tables found.</p>
    <?php endif; ?>
    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
  </div>
</body>
</html>

<?php
$conn->close();
?>
