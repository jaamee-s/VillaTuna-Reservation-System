<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect helper
function redirect($url) {
    echo "<script>window.location.href='$url';</script>";
    exit();
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['user']); // assumes admin login sets $_SESSION['user']
}

// Check if customer is logged in
function isCustomerLoggedIn() {
    return isset($_SESSION['customer_id']);
}

// Get logged in customer's name
function getCustomerName() {
    return $_SESSION['customer_name'] ?? 'Guest';
}

// Logout user/admin
function logout() {
    session_unset();
    session_destroy();
    redirect('../index2.php'); // or modify for different logout target
}
?>