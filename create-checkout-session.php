<?php
require 'stripe-php/init.php'; // Include the Stripe PHP library

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

\Stripe\Stripe::setApiKey('sk_test_51PQxiHBLnvKcgRvnEyzwhZ14RqZQ147qXZe9EQsZcWh5P2JMp0f3YJkbVlkncr80Ux97U4I5e4qTW8oM3GFhoCu400Co9ZuqLK'); // Replace with your Stripe secret key

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$price = $data['price'];
$name = $data['name'];
$eventID = $data['eventID'];
$quantity = $data['quantity'];

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $name,
                ],
                'unit_amount' => $price,
            ],
            'quantity' => $quantity,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost/ticketshop/success.php',
        'cancel_url' => 'http://localhost/ticketshop/cancel.php',
    ]);

    echo json_encode(['id' => $session->id]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
