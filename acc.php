<?php

include ("includes/functions.php");
include ("includes/header.php");
?>
    <script>setScroll();</script>
    <?php if (isset($_GET['actionuserDataChange'])): ?>
    <script>getScroll();</script>
    <?php endif ?>
    <main>
        <div class="heading-info">
            <h2>Moj račun</h2>
        </div>
        <div class="heading-small">
            <h2><?php if (isset($_SESSION['loggedIn'])) echo "Korisnik: " . $_SESSION['fname'] . " " . $_SESSION['lname']; else echo "Niste prijavljeni"?></h2>
        </div>
        <div class="account-container">
            <?php if (!isset($_SESSION['loggedIn'])) : ?>
            <div class="login-or-register">
            <span>Da bi ste pristupili svojim podacima potrebno je biti prijavljen na svoj račun.</span>
            <button onclick="window.location.href='register.php'">REGISTRACIJA</button>
            <span>ILI</span>
            <button onclick="window.location.href='login.php'">PRIJAVA</button>
            </div>
            <?php endif ?>
            <?php if (isset($_SESSION['loggedIn'])) : ?>
            <div class="user-personal-data">
            <span class="user-update"><text><?php echo $errorText; ?></text><text style="color: lightgreen"><b><?php echo $successText; ?></b></text></span>
            <span class="data-change">IZMJENA PODATAKA</span>
                <form class="data-action" action="acc.php?actionuserDataChange" method="post">
                    <div class="data-container">
                        <label for="fname">Ime</label>
                        <label for="lname">Prezime</label>
                        <input type="text" id="fname" name="fname" placeholder="Ime" value="<?php echo $_SESSION['fname']; ?>" disabled>
                        <input type="text" id="lname" name="lname" placeholder="Prezime" value="<?php echo $_SESSION['lname']; ?>" disabled>
                        <label for="email">E-mail adresa</label>
                        <label for="contact">Kontakt</label>
                        <input type="text" id="email" name="email" placeholder="E-mail" value="<?php echo $_SESSION['email']; ?>" disabled>
                        <input type="text" id="contact" name="contact" placeholder="Kontakt" value=<?php echo $_SESSION['contact']; ?> disabled>
                        <label for="adress">Adresa</label>
                        <label for="city">Grad</label>
                        <input type="text" id="adress" name="adress" placeholder="Adresa" value="<?php echo $_SESSION['adress']; ?>" disabled>
                        <input type="text" id="city" name="city" placeholder="Grad" value="<?php echo $_SESSION['city']; ?>" disabled>
                        <label for="zip">Poštanski broj</label>
                        <label for="emptyinput"></label>
                        <input type="text" id="zip" name="zip" placeholder="Poštanski broj" value="<?php echo $_SESSION['zip']; ?>" disabled> 
                    </div>
                    <div class="action-container">
                        <button id="edit-button" onclick="enableEditting();" form="none">IZMJENA PODATAKA</button>
                        <button type="submit" id="confirm-changes-button" name="confirm_changes" hidden>POTVRDI IZMJENE</button> 
                    </div>
                </form>   
            </div>
        </div>
        <div class="heading-small">
            <h2>Moje narudžbe</h2>
        </div>
        <div style="background-color: rgba(129, 129, 129, .1)">
        <div class="last-order-details" style="font-family: Century Gothic; color: rgb(129, 129, 129)">
            <?php

            $userId = $_SESSION['userId'];

            $orderSelectQuery = "SELECT * FROM `order` WHERE user_id_fk = '$userId' AND completion = 1";
            $orderSelectResult = mysqli_query($connection, $orderSelectQuery);
            $count = mysqli_num_rows ($orderSelectResult);
            $j=0;
            ?>
            <?php while ($orderSelectRow = mysqli_fetch_assoc($orderSelectResult)) : ?>
                <?php $j++ ?>
                <div style="<?php //if ($j % 2 == 0) echo "background-color: rgba(129, 129, 129, .5)"; ?>">
                <br>
                <?php $i=0; ?>
                <div class="last-order-title">
                    <span>Narudžba <?php echo  $orderSelectRow['order_code']; ?><span>
                </div>
                <div class="last-order-data">
                    <span>Ime i prezime</span>
                    <span>Adresa</span>
                    <span>Kontakt</span>
                    <span>Način dostave</span>
                    <text class="mobile-display"><?php echo $orderSelectRow['order_user']; ?></text>
                    <text class="mobile-display"><?php echo $orderSelectRow['order_adress']; ?></text>
                    <text class="mobile-display"><?php echo $orderSelectRow['order_contact']; ?></text>
                    <text class="mobile-display"><?php echo $orderSelectRow['delivery']; ?></text>
                    <span>Način plaćanja</span>
                    <span>Količina proizvoda</span>
                    <span>Ukupna cijena</span>
                    <span>Datum i vrijeme</span>
                    <text class="mobile-hidden"><?php echo $orderSelectRow['order_user']; ?></text>
                    <text class="mobile-hidden"><?php echo $orderSelectRow['order_adress']; ?></text>
                    <text class="mobile-hidden"><?php echo $orderSelectRow['order_contact']; ?></text>
                    <text class="mobile-hidden"><?php echo $orderSelectRow['delivery']; ?></text>
                    <text><?php echo $orderSelectRow['payment']; ?></text>
                    <text><?php echo $orderSelectRow['order_quantity']; ?></text>
                    <text><?php echo $orderSelectRow['subtotal'] . " kn"; ?></text>
                    <text><?php echo $orderSelectRow['time']; ?></text>
                </div>
                <div class="last-order-cart-item-data">
                    <div class="cart-item-title">
                        <span>Sadržaj narudžbe</span>
                    </div> 
                    <div class="cart-item-data">
                    <span>#</span>
                    <span>Naziv proizvoda</span>
                    <span class="mobile-hidden-always">Šifra proizvoda</span>
                    <span>Cijena</span>
                    <span>Količina</span>
                    <span>Iznos</span>
                <?php 

                $orderId = $orderSelectRow['order_id'];

                $cartItemSelectQuery = "SELECT * FROM cart_item WHERE order_id_fk = '$orderId'";
                $cartItemSelectResult = mysqli_query($connection, $cartItemSelectQuery);
                
                ?>
                <?php while ($cartItemSelectRow = mysqli_fetch_assoc($cartItemSelectResult)) : ?>
                        <?php $i++; ?>    
                        <text><?php echo $i; ?></text>    
                        <text><?php echo $cartItemSelectRow['ci_name']; ?></text>
                        <text class="mobile-hidden-always"><?php echo $cartItemSelectRow['ci_code']; ?></text>
                        <text><?php echo number_format($cartItemSelectRow['ci_price'], 2, ',', ' ') . " kn"; ?></text>
                        <text><?php echo $cartItemSelectRow['ci_quantity']; ?></text>
                        <text><?php echo number_format($cartItemSelectRow['total_price'], 2, ',', ' ') . " kn"; ?></text>
                <?php endwhile ?>
                    </div>
                </div><hr>
                </div>
            <?php endwhile ?>
        </div>
        </div>
        <?php endif ?>
    </main>
<?php

include ("includes/footer.php");
?>