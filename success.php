<?php 

include ("includes/functions.php");
include ("includes/header.php");
unset($_SESSION["shopping_cart"]);
?>

<main>
    <div class="success-container">
        <span>Uspjeh! Vaša nardužba je zaprimljena. <br> Hvala na narudžbi i ukazanom povjerenju.</span><hr width="50%">
        <img src="images/success.png" alt="Uspjeh!" width="100px"> <br>
        <span class="index-link"><a href="index.php?success=1">Natrag na početnu stranicu</a></span>
        <div class="last-order-details">
            <span>Detalji Vaše narudžbe<span>
                <hr>
            <?php

            if (isset($_SESSION['loggedIn'])) {
                $userId = $_SESSION['userId'];
            }
        
            else if (!isset($_SESSION['loggedIn'])) {
                $userId = $_SESSION['guestId'];
            }
        
            $orderSelectQuery = "SELECT * FROM `order` WHERE user_id_fk = '$userId' AND completion = 1 ORDER BY `time` DESC LIMIT 1";
            $orderSelectResult = mysqli_query($connection, $orderSelectQuery);
            $orderSelectRow = mysqli_fetch_assoc($orderSelectResult);

            $orderId = $orderSelectRow['order_id'];

            $cartItemSelectQuery = "SELECT * FROM cart_item WHERE order_id_fk = '$orderId'";
            $cartItemSelectResult = mysqli_query($connection, $cartItemSelectQuery);

            $i = 0;
            ?>
        </div>
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
        </div>
        <hr>
        <button onclick='window.location.href="index.php?success=1"' class="index-button">NATRAG NA POČETNU STRANICU</button>
        <div>
            <hr width="25%">
            <span>Vaš</span>
            <div><img src="images/HoW_Logo4.png" alt="House of Wine" width="250px"></div>
        </div>
    </div>
</main> 