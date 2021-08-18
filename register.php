<?php

include ("includes/functions.php");
include ("includes/header.php");
?>  
    <script>setScroll();</script>
    <?php if (isset($_GET['actionregister'])): ?>
    <script>getScroll();</script>
    <?php endif ?>
    <main>
        <div class="heading-info">
            <h2>Registracija</h2>
        </div>
        <div class="heading-small">
            <h2>Nemate račun kod nas?</h2>
        </div>
        <div class="account-container login-register">
            <div class="register-container">
                <h4>Registrirajte se</h4>
                <form action="register.php?actionregister" method="post">
                    <?php if (strpos($error, 'emailExisting') !== false) : ?>
                        <p class="error-text existing-account">Račun s ovim e-mailom već je registriran.</p>
                    <?php endif ?>
                    <input type="text" id="email" name="email" placeholder="E-mail adresa" value="<?php echo $email; ?>"><br>
                    <?php if (strpos($error, 'emailEmpty') !== false) : ?>
                        <p class="error-text">Niste unijeli e-mail adresu.</p>
                    <?php endif ?>
                    <?php if (strpos($error, 'emailInvalid') !== false && strpos($error, 'emailEmpty') === false) : ?>
                        <p class="error-text">Neispravna e-mail adresa.</p>
                    <?php endif ?>
                    <input type="password" id="password" name="password" placeholder="Lozinka" value="<?php echo $password; ?>"><br>
                    <?php if (strpos($error, 'passwordEmpty') !== false) : ?>
                        <p class="error-text">Niste unijeli lozinku</p>
                    <?php endif ?>
                    <?php if (strpos($error, 'passwordInvalid') !== false && strpos($error, 'passwordEmpty') === false) : ?>
                        <p class="error-text">Lozinka mora sadržavati barem 6 znakova.</p>
                    <?php endif ?>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Potvrdite lozinku" value="<?php echo $confirmPassword; ?>"><br>
                    <?php if (strpos($error, 'passwordMismatch') !== false) : ?>
                        <p class="error-text">Lozinke se ne podudaraju.</p>
                    <?php endif ?>
                    <input type="text" id="fname" name="fname" placeholder="Ime" value="<?php echo $fname; ?>"><br>
                    <?php if (strpos($error, 'fnameEmpty') !== false) : ?>
                        <p class="error-text">Niste unijeli ime.</p>
                    <?php endif ?>
                    <input type="text" id="lname" name="lname" placeholder="Prezime" value="<?php echo $lname; ?>"><br>
                    <?php if (strpos($error, 'lnameEmpty') !== false) : ?>
                        <p class="error-text">Niste unijeli prezime</p>
                    <?php endif ?>
                    <input type="text" id="contact" name="contact" placeholder="Kontakt broj" value="<?php echo $contact; ?>"><br>
                    <?php if (strpos($error, 'contactEmpty') !== false) : ?>
                        <p class="error-text">Niste unijeli kontakt broj.</p>
                    <?php endif ?>
                    <input type="text" id="adress" name="adress" placeholder="Adresa" value="<?php echo $adress; ?>"><br>
                    <?php if (strpos($error, 'adressEmpty') !== false) : ?>
                        <p class="error-text">Niste unijeli adresu.</p>
                    <?php endif ?>
                    <input type="text" id="city" name="city" placeholder="Grad" value="<?php echo $city; ?>"><br>
                    <?php if (strpos($error, 'cityEmpty') !== false) : ?>
                        <p class="error-text">Niste unijeli grad.</p>
                    <?php endif ?>
                    <input type="text" id="zip" name="zip" placeholder="Poštanski broj" value="<?php echo $zip; ?>""><br>
                    <?php if (strpos($error, 'zipEmpty') !== false) : ?>
                        <p class="error-text">Niste unijeli poštanski broj.</p>
                    <?php endif ?>
                    <button class="register-btn" type="submit" name="register">REGISTRACIJA</button>
                </form>
                <h5>Već imate račun? <a href="login.php">Prijavite se.</a></h6>
            </div>
        </div>
    </main>
<?php

include ("includes/footer.php");
?>