<?php

include ("includes/functions.php");
include ("includes/header.php");
?>
    <script>setScroll();</script>
    <?php if (isset($_GET['actionlogin'])): ?>
    <script>getScroll();</script>
    <?php endif ?>
    <main>
        <div class="heading-info">
            <h2>Moj račun</h2>
        </div>
        <div class="heading-small">
            <h2>Postojeći korisnik?</h2>
        </div>
        <div class="account-container login-register">
            <div class="login-container">
                <h4>Prijavite se</h4>
                <form action="login.php?actionlogin" method="post">
                    <input type="text" id="email" name="email" placeholder="E-mail adresa" value="<?php echo $email; ?>"><br>
                    <?php if (strpos($error, 'emailEmpty') !== false) : ?>
                        <p class="error-text">Niste unijeli e-mail adresu.</p>
                    <?php endif ?>
                    <?php if (strpos($error, 'emailWrong') !== false && strpos($error, 'emailEmpty') === false) : ?>
                        <p class="error-text">Nepostojeća e-mail adresa.</p>
                    <?php endif ?>
                    <input type="password" id="password" name="password" placeholder="Lozinka" value="<?php echo $password; ?>"><br>
                    <?php if (strpos($error, 'passwordEmpty') !== false && strpos($error, 'emailWrong') === false) : ?>
                        <p class="error-text">Niste unijeli lozinku</p>
                    <?php endif ?>
                    <?php if (strpos($error, 'passwordWrong') !== false && strpos($error, "passwordEmpty") === false) : ?>
                        <p class="error-text">Neispravna lozinka.</p>
                    <?php endif ?>
                    <button class="login-btn" type="submit" name="login">PRIJAVA</button>
                </form>
                <h5>Nemate račun kod nas? <a href="register.php">Registrirajte se.</a></h6>
            </div>
        </div>
    </main>
<?php

include ("includes/footer.php");
?>