<?php 

include ("includes/functions.php");
include ("includes/header.php");
?>
<main>
    <div class="body-container">
        <div class="slider_quote">
            <div class="slider background-img">
                <h2>Dobrodošli<br>
                        <?php if (isset($_SESSION['loggedIn'])) : ?>
                        <b><?php echo $_SESSION['fname'] . "."; ?></b>
                        <br> <a href="index.php?logout='1'"><button class="logout-btn">ODJAVA</button></a>
                    <?php endif ?>
                    <?php if (!isset($_SESSION['loggedIn'])) : ?>
                        <p> Niste prijavljeni.
                            <br><a href="login.php"><b>Prijavite se</b></a> ili <a href="register.php"><b>se registrirajte</b></a> ukoliko nemate račun.
                        </p> 
                    <?php endif ?>
                </h2>
            </div>
            <div class="quote background-img">
                "Vino raduje čovjeka, a radost je majka svih vrlina."
                <div>
                    -Johann Wolfgang von Goethe
                </div>
            </div>
        </div>
        <div class="content1 background-img">
            <a href="oprema.php" class="link">
                <span>
                    <h2>Posebna ponuda otvarača, dekantera i ostale opreme za istinske ljubitelje</h2>
                </span>
            </a>
        </div>
        <div class="content2 background-img">
            <a href="special.php" class="link2">
                <span>
                    <h2>Posebna ponuda</h2>
                </span>
            </a>
        </div>
        <div class="content3 background-img">
            <a href="podrum.php?filter=red" class="link3">
                <span>
                    <h2>Crna vina</h2>
                </span>
            </a>
        </div>
        <div class="content4 background-img">
            <a href="sirevi.php" class="link4">
                <span>
                    <h2>Izbor sireva za gurmane</h2>
                </span>
            </a>
        </div>
    </div>
</main>

<?php

include ("includes/footer.php");
?>