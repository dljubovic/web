<?php

include ("includes/functions.php");
include ("includes/header.php");

$email = "1";
$visibility = "";
$visibility2= "";
?>
    <script>setScroll();</script>
    <?php if (isset($_GET['actionbuttonRadio']) || isset($_GET['back']) || isset($_GET['continue']) || $_GET['state'] == "final") : ?>
    <script>getScroll();</script>
    <?php endif ?>
    <main>
        <div class="heading-info">
            <h2>Završetak kupnje</h2>
        </div>
        <div class="heading-small">
            <h2><span class="step-back-container"><svg class="step-back-button" width="20pt" onclick='window.location.href=
            "<?php backButton(); ?>"'  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve">
                            <g>
                                <path d="M198.608,246.104L382.664,62.04c5.068-5.056,7.856-11.816,7.856-19.024c0-7.212-2.788-13.968-7.856-19.032l-16.128-16.12
			C361.476,2.792,354.712,0,347.504,0s-13.964,2.792-19.028,7.864L109.328,227.008c-5.084,5.08-7.868,11.868-7.848,19.084
			c-0.02,7.248,2.76,14.028,7.848,19.112l218.944,218.932c5.064,5.072,11.82,7.864,19.032,7.864c7.208,0,13.964-2.792,19.032-7.864
			l16.124-16.12c10.492-10.492,10.492-27.572,0-38.06L198.608,246.104z" />
                            </g>
                    </svg></span><?php
                                    if (checkStep() == "delivery") { 
                                        echo "Podaci o dostavi"; 
                                    }

                                    else if (checkStep() == "payment") {
                                        echo "Podaci o plaćanju";
                                    }

                                    else if (checkStep() == "final") {
                                        echo "Potvrda";
                                    }
                                ?></h2>
        </div>
        <div class="checkout-container">
            <div class="checkout-left-container">
                <div class="steps-container">
                    <div class="step-1">
                        <span class="step-1-title">Odabir dostave</span>
                        <span class="step-1-circle <?php if (checkStep() == "delivery" || checkStep() == "payment" || checkStep() == "final") echo "current-step"; ?>">1</span>
                    </div>
                    <div class="step-line <?php if (checkStep() == "payment" || checkStep() == "final") echo "current-step"; ?>"></div>
                    <div class="step-2">
                        <span class="step-2-title"><div>Način plaćanja</div></span>
                        <span class="step-2-circle <?php if (checkStep() == "payment" || checkStep() == "final") echo "current-step"; ?>">2</span>
                    </div>
                    <div class="step-line step-line-2 <?php if (checkStep() == "final") echo "current-step"; ?>"></div>
                    <div class="step-3">
                        <span class="step-3-title"><div>Završetak kupnje</div></span>
                        <span class="step-3-circle <?php if (checkStep() == "final") echo "current-step"; ?>">3</span>
                    </div>
                </div>
                <div class="<?php if (checkStep() !== "final") echo "radio-buttons-container"; else echo "order-data-container"; ?>">
                    <span>
                            <?php 

                            if (isset($_SESSION['loggedIn'])) {
                                echo "Prijavljeni ste kao <b>" . $_SESSION['fname'] . " " . $_SESSION['lname'] . ".</b>";
                            }

                            else {
                            echo  "Niste prijavljeni, kupujete kao gost. <br> 
                            Da biste pratili svoje narudžbe i lakše izvršili unos podataka <b><a href='login.php' class='login-message'>prijavite se</a></b> na svoj račun.";
                            }
                            ?>
                    </span><br><?php if (checkStep() !== "final") echo "<br><hr>"; ?><br>
                <?php if (checkStep() !== "final") : ?>
                    <?php 
                        $page = $_SERVER['REQUEST_URI'];
                        $pageAction = "&actionbuttonRadio";

                        if (strpos($page, 'actionbuttonRadio') !== false) {
                            $pageAction = "";
                        } 
                    ?>
                    <form action="<?php echo $page . $pageAction; ?>" method="post">
                        <h2><?php if (checkStep() == "delivery") echo "Odaberite način dostave:"; else if (checkStep() == "payment") echo "Odaberite način plaćanja:"; ?></h2>
                        <?php if (checkStep() !== "delivery") { $visibility = "hidden"; } if (checkStep() !== "payment") { $visibility2 = "hidden"; } ?> 
                        
                        <input onclick="this.form.submit()" type="radio" class="yes" name="<?php echo "delivery"; ?>" value="yes"
                        <?php if (deliveryOption() === true || $_GET['delivery'] == "yes") { echo "checked"; } ?> <?php echo $visibility; ?>><?php if (checkStep() == "delivery") echo "Dostava na adresu (30 kn)"; ?>

                        <input onclick="this.form.submit()" type="radio" class="no" name="<?php echo "delivery"; ?>" value="no"
                        <?php if (deliveryOption() === false || $_GET['delivery'] == "no") { echo "checked"; } ?> <?php echo $visibility; ?>><?php if (checkStep() == "delivery") echo "Osobno ću pokupiti"; ?>
                    
                        <input onclick="this.form.submit()" type="radio" class="yes" name="<?php echo "payment"; ?>" value="cash" 
                        <?php if (paymentOption() === true || $_GET['payment'] == "cash") { echo "checked"; } ?> <?php echo $visibility2; ?>><?php if (checkStep() == "payment") echo "Gotovina (pouzećem)"; ?>

                        <input onclick="this.form.submit()" type="radio" class="no" name="<?php echo "payment"; ?>" value="card"
                        <?php if (paymentOption() === false || $_GET['payment'] == "card") { echo "checked"; } ?> <?php echo $visibility2; ?>><?php if (checkStep() == "payment") echo "Virmansko"; ?>
                    </form>
                    <form action="" id="user-data-form">
                            <input type="text" id="state" name="state" value="<?php echo checkNext(); ?>" hidden>
                            <input type="text" id="delivery" name="delivery" value="<?php if (checkStep() !== "delivery" ) echo $_GET['delivery']; else echo continueButton(); ?>" hidden>
                            <input type="text" id="payment" name="payment" value="<?php if (checkStep() !== "payment" ) echo $_GET['payment']; else echo continueButton(); ?>" hidden>
                            <input type="text" id="final" name="final" value = "0" hidden>
                    </form>
                    <br>
                    <span class="small-warning-message"><?php if (deliveryOption() !== true && deliveryOption() !== false && checkStep() !== "final") { if (paymentOption() !== true && paymentOption() !== false) { echo "Za nastavak morate odabrati jednu od ponuđenih opcija."; } } ?></span>
                <?php endif ?>
                <?php if (checkStep() == "final") : ?>
                    <span>Prije potvrde kupnje provjerite ispravnost podataka i sadržaj Vaše narudžbe.</span><br><br><hr><br>
                    <span style="color: red" <?php echo $visibility; ?>>* Obavezna polja</span>
                    <form action="" id="order-data-form" method="post">
                        <div class="user-data-enabled">
                            <h2 <?php if (checkStep() !== "final") { $visibility = "hidden"; } echo $visibility?>>Podaci o kupcu</h2>

                            <input type="text" id="fname" name="fname" placeholder="Ime*" value="<?php echo $orderFname; ?>"<?php echo $visibility; ?>><br>
                            <?php if (strpos($orderError, 'fnameEmpty') !== false) : ?><span class="error-text">Niste unijeli ime</span><br><?php endif ?>

                            <input type="text" id="lname" name="lname" placeholder="Prezime*" value="<?php echo $orderLname; ?>"<?php echo $visibility; ?>><br>
                            <?php if (strpos($orderError, 'lnameEmpty') !== false) : ?><span class="error-text">Niste unijeli prezime</span><br><?php endif ?>

                            <input type="text" id="email" name="email" placeholder="E-mail adresa*" value="<?php echo $orderEmail; ?>"<?php echo $visibility; ?>><br>
                            <?php if (strpos($orderError, 'emailEmpty') !== false) : ?><span class="error-text">Niste unijeli e-mail adresu</span><br><?php endif ?>
                            <?php if (strpos($orderError, 'emailInvalid') !== false && strpos($orderError, 'emailEmpty') === false) : ?><span class="error-text">Neispravna e-mail adresa</span><br><?php endif ?>

                            <input type="text" id="contact" name="contact" placeholder="Kontakt*" value="<?php echo $orderContact; ?>"<?php echo $visibility; ?>><br>
                            <?php if (strpos($orderError, 'contactEmpty') !== false) : ?><span class="error-text">Niste unijeli kontakt</span><br><?php endif ?>
                                
                            <input type="text" id="state" name="state" value="<?php echo checkNext(); ?>" hidden>
                            <input type="text" id="delivery" name="delivery" value="<?php if (checkStep() !== "delivery" ) echo $_GET['delivery']; else echo continueButton(); ?>" hidden>
                            <input type="text" id="payment" name="payment" value="<?php if (checkStep() !== "payment" ) echo $_GET['payment']; else echo continueButton(); ?>" hidden>
                            <input type="text" id="final" name="final" value = "0" hidden>
                        </div>
                        <div class="delivery-data<?php if (deliveryOption() === true || $_GET['delivery'] == "yes") { echo "-enabled"; } ?>">
                            <h2 <?php echo $visibility; ?>>Podaci za dostavu</h2>
                            
                            <input type="text" id="adress" name="adress" placeholder="Adresa*" value="<?php echo $orderAdress; ?>"
                            <?php if (deliveryOption() === true || $_GET['delivery'] == "yes") { echo "enabled"; } if (deliveryOption() !== true && checkStep() == "final") echo "disabled"; ?> <?php echo $visibility; ?>><br>
                            <?php if (strpos($orderError, 'adressEmpty') !== false) : ?><span class="error-text">Niste unijeli adresu</span><br><?php endif ?>
                            
                            <input type="text" id="city" name="city" placeholder="Grad*" value="<?php echo $orderCity; ?>"
                            <?php if (deliveryOption() === true || $_GET['delivery'] == "yes") { echo "enabled"; } if (deliveryOption() !== true && checkStep() == "final") echo "disabled"; ?> <?php echo $visibility; ?>><br>
                            <?php if (strpos($orderError, 'cityEmpty') !== false) : ?><span class="error-text">Niste unijeli grad</span><br><?php endif ?>
                            
                            <input type="text" id="zip" name="zip" placeholder="Poštanski broj*" value="<?php echo $orderZip; ?>"
                            <?php if (deliveryOption() === true || $_GET['delivery'] == "yes") { echo "enabled"; } if (deliveryOption() !== true && checkStep() == "final") echo "disabled"; ?> <?php echo $visibility; ?>><br>
                            <?php if (strpos($orderError, 'zipEmpty') !== false) : ?><span class="error-text">Niste unijeli poštanski broj</span><br><?php endif ?>
                        </div>
                    </form>
                <?php endif ?>
                </div>
                <?php if (checkStep() == "final") : ?>
                    <h2 class="naslov">Sadržaj narudžbe</h2>
                    <div class="in-order">
                    <div class="title-bar-container" style="color: rgb(129, 129, 129);">
                        <div class="title-name">
                            proizvod
                        </div>
                        <div class="title-price">
                            cijena
                        </div>
                        <div class="title-quantity">
                            količina
                        </div>
                        <div class="title-total">
                            iznos
                        </div>
                    </div>
                    <br>
                    <?php foreach($_SESSION["shopping_cart"] as $product) : ?>
                        <div class="info-bar-container" style="color: rgb(129, 129, 129); font-weight: normal">
                            <div class="product-img-name-remove">
                                <div class="product-img">
                                    <img src='<?php echo $product["image"]; ?>' alt="Proizvod u kosarici">
                                </div>
                                <div class="product-name-remove">
                                    <div class="product-name">
                                        <a href="product.php?code=<?php echo $product["code"] . "&ptype=" . $product["ptype"]; ?>"><?php echo $product["pname"]; ?></a>
                                    </div>
                                    <div class="product-remove">
                                        <?php echo $product["code"]; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="product-price">
                                <?php echo number_format($product["price"], 2, ',', ' ') . " kn"; ?>
                            </div>
                            <div class="product-quantity">
                                <?php echo $product["quantity"]; ?>
                            </div>
                            <div class="product-total">
                                <?php
                                echo number_format($product["price"] * (int)$product["quantity"], 2, ',', ' ') . " kn";
                                ?>
                            </div>
                        </div><br>
                        
                        <?php endforeach ?> 
                        </div>                    
                <?php endif ?>
            </div>
            <div class="checkout-right-container <?php if (checkStep() == "final") { echo "translate-container"; } ?>">
                <div class="cart-subtotal-container">
                    <div class="subtotal-title">
                        Pregled košarice
                    </div>
                    <div class="subtotal-info">
                        <div class="items-overview">
                            <div class="quantity-overview">
                                <div class="quantity-title">
                                    Broj proizvoda
                                </div>
                                <div class="quantity-value">
                                    <?php echo $_SESSION['counter']; ?>
                                </div>
                            </div>
                            <div class="price-overview">
                                <div class="price-title">
                                    Ukupna cijena
                                </div>
                                <div class="price-value">
                                    <?php echo $_SESSION['totalPrice'] . " kn"; ?>
                                </div>
                                <div class="delivery-option">
                                    Dostava
                                </div>
                                <div class="delivery-price">
                                    <?php

                                    $deliveryPrice = 0.00;

                                    if (deliveryOption() === true || $_GET['delivery'] == "yes") {
                                        $deliveryPrice = 30.00;
                                    }

                                    echo number_format($deliveryPrice, 2, ',', ' ') . " kn";
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="subtotal-price">
                            <div class="subtotal">
                                Košarica ukupno
                            </div>
                            <div class="subtotal-value">
                                <?php

                                if (deliveryOption() === true || $_GET['delivery'] == "yes") {
                                    echo $_SESSION['subtotalPrice'] . " kn";
                                } 

                                else {
                                    echo $_SESSION['totalPrice'] . " kn";
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="buy-button">
                    <button  <?php if (checkStep() === "final") echo "hidden"; ?> form="user-data-form" 
                     type="submit" name="continue" class="buy-btn"<?php if (deliveryOption() !== true && deliveryOption() !== false) { if (paymentOption() !== true && paymentOption() !== false) { echo "style='opacity: 50%'" . "disabled"; } } ?>>
                    NASTAVI</button>
                    <button  <?php if (checkStep() !== "final") echo "hidden"; ?> form="order-data-form"
                     type="submit" name="confirm" class="buy-btn">
                    POTVRDI KUPNJU</button>
                </div>
            </div>
        </div>
    </main>
<?php

include ("includes/footer.php");
?>