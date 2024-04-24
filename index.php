<?php
include("backend/Auth.php");
require("backend/db_con.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Shop</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

<header>
    <div class="container">
        <nav>
            <ul>
                <h1>Ticket Shop</h1>
                <li><a href="#">Home</a></li>
                <li><a href="#">Tickets</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>

<section id="hero">
    <div class="container">
        <h2>Welcome to our Ticket Shop!</h2>
        <p>Find the best tickets for your favorite events.</p>
        <a href="#" class="btn">Browse Tickets</a>
    </div>
</section>

<section id="featured-tickets">
    <div class="container">
        <h2>Featured Tickets</h2>
        <div class="ticket-grid">
            <!-- Featured ticket items will be dynamically added here -->
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2024 Ticket Shop. All rights reserved.</p>
    </div>
</footer>

</body>
</html>