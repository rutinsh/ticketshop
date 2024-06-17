<?php
session_start();
require('backend/db_con.php');
require 'vendor/autoload.php';
require 'stripe-php/init.php';// Ensure this path is correct


use Stripe\Stripe;
use Stripe\Checkout\Session;

if (!isset($_SESSION['email'])) {
    echo "You must be logged in to view this page.";
    exit;
}

$email = $_SESSION['email'];
$session_id = $_GET['session_id'];
$eventID = $_GET['event_id'];
$quantity = $_GET['quantity'];

Stripe::setApiKey('sk_test_51PQxiHBLnvKcgRvnEyzwhZ14RqZQ147qXZe9EQsZcWh5P2JMp0f3YJkbVlkncr80Ux97U4I5e4qTW8oM3GFhoCu400Co9ZuqLK'); // Replace with your Stripe secret key

try {
    $session = Session::retrieve($session_id);
    if (!$session) {
        die("Failed to retrieve session. Please check the session ID.");
    }

    $total_price = $session->amount_total / 100; // Convert cents to euros

    // Get user ID
    $sql = "SELECT ID FROM lietotaji WHERE Epasts = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("User not found.");
    }

    $userID = $user['ID'];

    // Check if event exists in events table
    $sql = "SELECT EventID FROM events WHERE ReferenceID = ? AND EventType = 'koncerti'";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $eventID);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if (!$event) {
        // Insert event into events table
        $sql = "INSERT INTO events (EventType, ReferenceID) VALUES ('koncerti', ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $eventID);
        if (!$stmt->execute()) {
            die("Failed to insert event record: " . $stmt->error);
        }
        $newEventID = $stmt->insert_id;
    } else {
        $newEventID = $event['EventID'];
    }

    // Insert purchase record
    $sql = "INSERT INTO purchases (UserID, TotalPrice) VALUES (?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("id", $userID, $total_price);
    if (!$stmt->execute()) {
        die("Failed to insert purchase record: " . $stmt->error);
    }
    $purchaseID = $stmt->insert_id;

    $price = $session->amount_subtotal / $quantity / 100; // Calculate price per ticket

    for ($i = 0; $i < $quantity; $i++) {
        $sql = "INSERT INTO tickets (PurchaseID, EventID, Price) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iid", $purchaseID, $newEventID, $price);
        if (!$stmt->execute()) {
            die("Failed to insert ticket record: " . $stmt->error);
        }
    }

    echo "Purchase successful and tickets saved.";
    // Redirect to index.php after successful payment
    header("Location: index.php");
    exit;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
$connection->close();
?>
