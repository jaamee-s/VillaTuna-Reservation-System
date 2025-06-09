<?php
session_start();
include_once("../functions/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user'] === 'admin') {
    header("Location: ../index2.php");
    exit();
}

$username = $_SESSION['user']; // Get logged-in username

// Optional: Debug who is logged in
// echo "<div style='color: yellow;'>Logged in as: " . htmlspecialchars($username) . "</div>";

// Get customer_id for the current username
$customer_id = null;
$sql = "SELECT customer_id FROM tbl_customers WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($customer_id);
$stmt->fetch();
$stmt->close();

if (!$customer_id) {
    echo "<div style='color:white;background:red;padding:10px;'>Error: No customer ID found for username <strong>" . htmlspecialchars($username) . "</strong>.</div>";
    $conn->close();
    exit();
}

// Handle reservation cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_reservation_id'])) {
    $cancel_id = intval($_POST['cancel_reservation_id']);

    $update_stmt = $conn->prepare("UPDATE tbl_reservation SET status = 'canceled' WHERE reservation_id = ? AND customer_id = ?");
    $update_stmt->bind_param("ii", $cancel_id, $customer_id);
    $update_stmt->execute();
    $update_stmt->close();

    header("Location: view_reservations.php");
    exit();
}

// Fetch user's reservations
$sql = "SELECT reservation_id, reservation_date, reservation_time, table_id, staff_id, status 
        FROM tbl_reservation 
        WHERE customer_id = ? 
        ORDER BY reservation_date DESC, reservation_time DESC";
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
</head>
<body>
<div class="container mt-4">
  <h2>Your Reservations</h2>
  <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-striped table-bordered bg-white">
      <thead>
        <tr>
          <th>Date</th>
          <th>Time</th>
          <th>Table ID</th>
          <th>Staff ID</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['reservation_date']); ?></td>
            <td><?= htmlspecialchars($row['reservation_time']); ?></td>
            <td><?= htmlspecialchars($row['table_id']); ?></td>
            <td><?= htmlspecialchars($row['staff_id']); ?></td>
            <td>
              <?php
                if (strtolower(trim($row['status'])) === 'canceled') {
                  echo "<span class='badge bg-danger'>Canceled</span>";
                } else {
                  echo "<span class='badge bg-success'>Booked</span>";
                }
              ?>
            </td>
            <td>
              <?php if (strtolower(trim($row['status'])) !== 'canceled'): ?>
                <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?');" style="display:inline;">
                  <input type="hidden" name="cancel_reservation_id" value="<?= $row['reservation_id']; ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                </form>
              <?php else: ?>
                <button class="btn btn-secondary btn-sm" disabled>Already Canceled</button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="text-white">No reservations found.</p>
  <?php endif; ?>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
