<?php
session_start();
include_once("../functions/db.php");

// Only allow admin
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header("Location: ../index2.php");
    exit();
}

// Get reservation ID from URL and revert it to booked
if (isset($_GET['id'])) {
    $reservation_id = intval($_GET['id']);

    $stmt = $conn->prepare("UPDATE tbl_reservation SET status = 'booked' WHERE reservation_id = ?");
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: admin_reservations.php?msg=undo_success");
exit();
?>
