<?php
require("backend/db_con.php");
require 'stripe-php/init.php'; // Include the Stripe PHP library

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

\Stripe\Stripe::setApiKey('sk_test_51PQxiHBLnvKcgRvnEyzwhZ14RqZQ147qXZe9EQsZcWh5P2JMp0f3YJkbVlkncr80Ux97U4I5e4qTW8oM3GFhoCu400Co9ZuqLK'); // Replace with your Stripe secret key

if (!isset($_GET['id'])) {
    echo "Invalid Event ID.";
    exit;
}

$eventID = intval($_GET['id']);
$sql = "SELECT Nosaukums, Datums, Laiks, Informacija, Cena, Plakats FROM koncerti WHERE KoncertiID = $eventID";
$result = $connection->query($sql);

if ($result->num_rows == 0) {
    echo "Event not found.";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row["Nosaukums"]); ?></title>

    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="resources/CSS/index.css" rel="stylesheet">
    <style>
        .purchase-details {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .event-info, .ticket-summary {
            flex: 1;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .event-info {
            max-width: 40%;
        }
        .ticket-summary {
            max-width: 40%;
        }
        .event-info h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .event-info p {
            margin: 0.5rem 0;
        }
        .event-info img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .ticket-summary h2 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        .ticket-summary .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .ticket-summary .form-group label {
            margin-right: 10px;
        }
        .ticket-summary .form-group select {
            width: 100px;
        }
        .ticket-summary .price-details {
            display: none;
            margin-top: 10px;
        }
        .ticket-summary .total-price {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .btn-buy {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-buy:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
<?php include 'navbar.php'; ?>

<section class="purchase-details py-5 container">
    <div class="event-info">
        <img src="<?php echo htmlspecialchars($row["Plakats"]); ?>" alt="Event Image">
        <h1><?php echo htmlspecialchars($row["Nosaukums"]); ?></h1>
        <p><i class="fas fa-calendar-alt"></i> <?php echo date("d F Y, l", strtotime($row["Datums"])); ?>, <span><?php echo date("H:i", strtotime($row["Laiks"])); ?></span></p>
        <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row["Informacija"]); ?></p>
    </div>
    <div class="ticket-summary">
        <h2>Jūsu biļetes</h2>
        <div class="form-group">
            <label for="ticketQuantity">Biļešu skaits:</label>
            <select id="ticketQuantity" class="form-control">
                <option value="" selected disabled>Izvēlieties</option>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="price-details">
            <p class="price">Cena: <?php echo number_format($row["Cena"], 2); ?> EUR</p>
            <p class="total-price">Kopā: <span id="totalPrice"><?php echo number_format($row["Cena"], 2); ?></span></p>
        </div>
        <button id="checkout-button" class="btn-buy">Uz apmaksu</button>
    </div>
</section>

<!-- Stripe Checkout Script -->
<script>
    var stripe = Stripe('pk_test_51PQxiHBLnvKcgRvnLGGkpcSF4sejT6kzySw78kvSRVC2BabzAd2w2yrsH5v6Hyi4PHJnwpYTc98jMxJ2N7lKnqkn002hwtazqs'); // Replace with your Stripe publishable key
    var checkoutButton = document.getElementById('checkout-button');
    var ticketQuantity = document.getElementById('ticketQuantity');
    var totalPrice = document.getElementById('totalPrice');
    var priceDetails = document.querySelector('.price-details');

    ticketQuantity.addEventListener('change', function() {
        var price = <?php echo $row["Cena"]; ?>;
        var quantity = ticketQuantity.value;
        var total = (price * quantity).toFixed(2);
        totalPrice.textContent = total + ' EUR';
        priceDetails.style.display = 'block';
    });

    checkoutButton.addEventListener('click', function () {
        if (!ticketQuantity.value) {
            alert('Lūdzu, izvēlieties biļešu skaitu.');
            return;
        }

        fetch('create-checkout-session.php', {  // Ensure this URL is correct
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        price: <?php echo $row["Cena"] * 100; ?>,
        name: "<?php echo htmlspecialchars($row["Nosaukums"]); ?>",
        eventID: <?php echo $eventID; ?>,
        quantity: ticketQuantity.value
    }),
})
.then(function (response) {
    return response.json();
})
.then(function (sessionId) {
    if (sessionId.error) {
        alert(sessionId.error);
    } else {
        window.location.href = 'checkout.php?session_id=' + sessionId.id;
    }
})
.catch(function (error) {
    console.error('Error:', error);
});

    });
</script>

<!--Bootstrap JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>

<?php
$connection->close();
?>
