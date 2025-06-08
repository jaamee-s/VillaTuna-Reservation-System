<?php
session_start();
include_once("../functions/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user'] === 'admin') {
    header("Location: ../index2.php");
    exit();
}

// Handle deletion if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reservation_id'])) {
    $delete_id = intval($_POST['delete_reservation_id']);

    // Optional: Check that this reservation belongs to the logged-in user before deleting for security
    $username = $_SESSION['user'];
    $sql = "SELECT customer_id FROM tbl_customers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($customer_id_check);
    $stmt->fetch();
    $stmt->close();

    if ($customer_id_check) {
        $del_stmt = $conn->prepare("DELETE FROM tbl_reservation WHERE reservation_id = ? AND customer_id = ?");
        $del_stmt->bind_param("ii", $delete_id, $customer_id_check);
        $del_stmt->execute();
        $del_stmt->close();
    }
    // Redirect to avoid resubmission
    header("Location: view_reservations.php");
    exit();
}

// Get logged-in username
$username = $_SESSION['user'];

// Get the customer_id for this username
$sql = "SELECT customer_id FROM tbl_customers WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($customer_id);
if (!$stmt->fetch()) {
    // No customer found
    $stmt->close();
    $conn->close();
    echo "No reservations found.";
    exit();
}
$stmt->close();

// Fetch reservations for this customer, include reservation_id for deletion
$sql = "SELECT reservation_id, reservation_date, reservation_time, table_id, staff_id FROM tbl_reservation WHERE customer_id = ? ORDER BY reservation_date DESC, reservation_time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Your Reservations | Villatuna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
<body>
<div class="container mt-4">
  <h2>Your Reservations</h2>
  <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Date</th>
          <th>Time</th>
          <th>Table ID</th>
          <th>Staff ID</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['reservation_date']); ?></td>
            <td><?php echo htmlspecialchars($row['reservation_time']); ?></td>
            <td><?php echo htmlspecialchars($row['table_id']); ?></td>
            <td><?php echo htmlspecialchars($row['staff_id']); ?></td>
            <td>
              <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?');" style="display:inline;">
                <input type="hidden" name="delete_reservation_id" value="<?php echo $row['reservation_id']; ?>">
                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No reservations found.</p>
  <?php endif; ?>

</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
