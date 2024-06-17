<?php
session_start();
require 'backend/db_con.php';
require 'vendor/autoload.php';
require 'stripe-php/init.php';// Ensure this path is correct


\Stripe\Stripe::setApiKey('sk_test_51PQxiHBLnvKcgRvnEyzwhZ14RqZQ147qXZe9EQsZcWh5P2JMp0f3YJkbVlkncr80Ux97U4I5e4qTW8oM3GFhoCu400Co9ZuqLK'); // Replace with your Stripe secret key

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventID = $_POST['eventID'];
    $eventName = $_POST['eventName'];
    $eventPrice = $_POST['eventPrice'];
    $quantity = $_POST['quantity'];

    if (!$eventID || !$eventName || !$eventPrice || !$quantity) {
        die("Missing required parameters.");
    }

    $YOUR_DOMAIN = 'http://localhost/ticketshop';

    try {
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $eventName,
                    ],
                    'unit_amount' => $eventPrice * 100,
                ],
                'quantity' => $quantity,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.php?session_id={CHECKOUT_SESSION_ID}&event_id=' . $eventID . '&quantity=' . $quantity,
            'cancel_url' => $YOUR_DOMAIN . '/cancel.php',
        ]);

        header("Location: " . $checkout_session->url);
        exit;
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request method';
}

