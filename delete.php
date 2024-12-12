<?php
session_start();
include '../db.php';

// Check if the user is logged in and is a contributor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'contributor') {
    header("Location: ../login.php");
    exit();
}

// Check if 'id' (order_id) is set in the URL
if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']); // Sanitize the input to ensure it's an integer

    try {
        // Prepare DELETE SQL query
        $stmt = $pdo->prepare("DELETE FROM Innovation WHERE order_id = :order_id");
        $stmt->execute(['order_id' => $order_id]);

        // Redirect back to the contributor dashboard after deletion
        header("Location: contributor_dashboard.php");
        exit();
    } catch (PDOException $e) {
        // In case of an error, display the error message
        echo "Error deleting record: " . $e->getMessage();
    }
} else {
    // If no 'id' is found in the URL, show an error message
    echo "Invalid order ID.";
}
?>
