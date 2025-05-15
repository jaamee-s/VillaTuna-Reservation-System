<?php
include_once('db.php');

// Create a new reservation
function createReservation($customer_id, $table_id, $date, $time, $guests) {
    global $conn;

    // Check if the table is already booked for the given date and time
    $check = $conn->prepare("SELECT * FROM Reservations 
        WHERE table_id = ? AND reservation_date = ? AND reservation_time = ? AND status = 'Booked'");
    $check->bind_param("iss", $table_id, $date, $time);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        return "This table is already booked for that time.";
    }

    $stmt = $conn->prepare("INSERT INTO Reservations (customer_id, table_id, reservation_date, reservation_time, num_guests) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $customer_id, $table_id, $date, $time, $guests);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Failed to create reservation.";
    }
}

// Get all reservations (admin view)
function getAllReservations() {
    global $conn;
    $sql = "SELECT r.*, c.full_name, t.table_number
            FROM Reservations r
            JOIN Customers c ON r.customer_id = c.customer_id
            JOIN RestaurantTables t ON r.table_id = t.table_id
            ORDER BY reservation_date DESC, reservation_time DESC";
    $result = $conn->query($sql);
    return $result;
}

// Get reservations by customer (user view)
function getReservationsByCustomer($customer_id) {
    global $conn;
    $sql = "SELECT r.*, t.table_number
            FROM Reservations r
            JOIN RestaurantTables t ON r.table_id = t.table_id
            WHERE r.customer_id = ?
            ORDER BY reservation_date DESC, reservation_time DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Cancel reservation
function cancelReservation($reservation_id, $customer_id = null) {
    global $conn;

    // Optional: ensure the reservation belongs to the logged-in customer
    if ($customer_id !== null) {
        $check = $conn->prepare("SELECT * FROM Reservations WHERE reservation_id = ? AND customer_id = ?");
        $check->bind_param("ii", $reservation_id, $customer_id);
        $check->execute();
        $res = $check->get_result();
        if ($res->num_rows == 0) {
            return "Unauthorized cancellation attempt.";
        }
    }

    $stmt = $conn->prepare("UPDATE Reservations SET status = 'Cancelled' WHERE reservation_id = ?");
    $stmt->bind_param("i", $reservation_id);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Failed to cancel reservation.";
    }
}
?>
