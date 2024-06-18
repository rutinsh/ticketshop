<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>BiļešuBāze</title>
    <style>
        .navbar {
            background-color: #fff;
            height: 80px;
            margin: 20px;
            border-radius: 16px;
            padding: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 500;
            color: #2c18ca;
            font-size: 24px;
            transition: 0.3s color;
        }

        .login-button {
            background-color: #2088fc;
            color: #fff;
            font-size: 14px;
            padding: 8px 20px;
            border-radius: 50px;
            text-decoration: none;
            transition: 0.3s background-color;
        }

        .logout-button:hover {
            background-color: #12009e;
        }

        .logout-button {
            background-color: #2088fc;
            color: #fff;
            font-size: 14px;
            padding: 8px 20px;
            border-radius: 50px;
            text-decoration: none;
            transition: 0.3s background-color;
        }

        .login-button:hover {
            background-color: #12009e;
        }

        .navbar-toggler {
            border: none;
            font-size: 1.25rem;
        }

        .navbar-toggler:focus, .btn-close:focus {
            box-shadow: none;
            outline: none;
        }

        .nav-link {
            position: relative;
            color: #666777;
            font-weight: 500;
        }

        .nav-link:hover, .nav-link.active {
            color: #000;
        }

        #profileIcon {
            margin-right: 20px; /* Add margin to create space between the icon and the button */
        }

        #profileIcon:hover {
            color: #000;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand me-auto" href="index.php">BiļešuBāze</a>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">BiļešuBāze</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="koncerti.php">Koncerti</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="festivali.php">Festivāli</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="standup.php">Standup</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="citi.php">Citi</a>
                    </li>
                </ul>
            </div>
        </div>
        <?php
                if (isset($_SESSION['email'])) {
                    echo '<ul class="navbar-nav flex-row align-items-center">
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2" href="tickets.php">Manas biļetes</a>
                            </li>
                            <li class="nav-item">
                                <a href="profile.php" id="profileIcon"><i class="fas fa-user-circle fa-3x"></i></a>
                            </li>
                            <li class="nav-item">
                                <a href="logout.php" class="logout-button">Iziet</a>
                            </li>
                          </ul>';
                } else {
                    echo '<a href="login.php" class="login-button">Ienākt</a>';
                }
        ?>
        <button class="navbar-toggler pe-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>




