<?php
session_start();
include_once("../functions/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user'] === 'admin') {
    header("Location: ../index2.php");
    exit();
}

// Fetch available tables
$sql = "SELECT * FROM tbl_restauranttables ORDER BY table_number ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Choose a Table</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Choose a Table</h2>
    <?php if ($result->num_rows > 0): ?>
      <form action="reservations.php" method="POST">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="table-dark">
              <tr>
                <th>Select</th>
                <th>Table Number</th>
                <th>Capacity</th>
                <th>Location</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td>
                    <input type="radio" name="table_id" value="<?php echo $row['table_id']; ?>" required>
                  </td>
                  <td><?php echo htmlspecialchars($row['table_number']); ?></td>
                  <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                  <td><?php echo htmlspecialchars($row['location']); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Proceed to Reservation</button>
      </form>
    <?php else: ?>
      <p>No tables available at the moment.</p>
    <?php endif; ?>
    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
  </div>
</body>
</html>

<?php
$conn->close();
?>
