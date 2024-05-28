<nav class="navbar navbar-expand-lg fixed-top">
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
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="citi.php">Citi</a>
                      </li>
                  </li>
              </ul>
            </div>
          </div>
          <?php
            session_start();

            // Pārbauda, vai lietotājs ir autorizējies
            if(isset($_SESSION['email'])) {
                // Lietotājs ir autorizējies, tādēļ izvada pogu "Iziet"
                echo '<a href="#" id="profileIcon"><i class="fas fa-user-circle fa-lg"></i></a>

                <!-- Pop-up forma (sākotnēji paslēpta) -->
                <div id="popupForm" style="display: none;">
                  <form>
                    <!-- Šeit ievietojiet pop-up formas saturu -->
                    <h1>HELLO</h2>
                  </form>
                </div>';
            } else {
                // Lietotājs nav autorizējies, tādēļ izvada pogu "Ienākt"
                echo '<a href="login.php" class="login-button">Ienākt</a>';
            }
            ?>
          <button class="navbar-toggler pe-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </nav>