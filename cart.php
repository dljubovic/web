<?php

include ("includes/functions.php");
$status = "";

if (isset($_POST['action']) && $_POST['action'] == "remove") {

    if (!empty($_SESSION["shopping_cart"])) {

        foreach ($_SESSION["shopping_cart"] as $key => $value) {
            if ($_POST["code"] == $key) {

                unset($_SESSION["shopping_cart"][$key]);
                $status = "Proizvod je uklonjen iz košarice.";

                if (isset($_SESSION['loggedIn'])) {
                    $userId = $_SESSION['userId'];
                }

                else if (!isset($_SESSION['loggedIn'])) {
                    $userId = $_SESSION['guestId'];
                }

                $orderSelectQuery = "SELECT * FROM `order` WHERE user_id_fk = '$userId' AND completion = 0 LIMIT 1";
                $orderSelectResult = mysqli_query($connection, $orderSelectQuery);
                $orderSelectRow = mysqli_fetch_assoc($orderSelectResult);

                $orderId = $orderSelectRow['order_id'];

                $cartItemQuery = "DELETE FROM cart_item WHERE order_id_fk = '$orderId' AND ci_code = '$key'";
                mysqli_query($connection, $cartItemQuery);
                
                header("Location: cart.php?actionproductRemove");
            }

            if (empty($_SESSION["shopping_cart"])) {

                unset($_SESSION["shopping_cart"]);

                if (isset($_SESSION['loggedIn'])) {
                    $userId = $_SESSION['userId'];
                    destroyCookie();
                }

                else if (!isset($_SESSION['loggedIn'])) {
                    $userId = $_SESSION['guestId'];
                }

                $orderQuery = "DELETE FROM `order` WHERE user_id_fk = '$userId' AND completion = 0";
                mysqli_query($connection, $orderQuery);
                
                if (!isset($_SESSION['loggedIn'])) {
                    $guestQuery = "DELETE FROM user WHERE user_id = '$userId'";
                    mysqli_query($connection, $guestQuery);
                }
            }
        }
    }
}

if (isset($_POST['action']) && $_POST['action'] == "change") {

    foreach ($_SESSION["shopping_cart"] as &$value) {

        if ($value['code'] === $_POST["code"]) {
            
            $value['quantity'] = $_POST["quantity"];
            $quantity = $_POST["quantity"];
            $code = $_POST["code"];

            if (isset($_SESSION['loggedIn'])) {
                $userId = $_SESSION['userId'];
            }

            else if (!isset($_SESSION['loggedIn'])) {
                $userId = $_SESSION['guestId'];
            }

            $cartItemSelectQuery = "SELECT ci_price FROM cart_item WHERE ci_code = '$code'";
            $cartItemSelectResult = mysqli_query($connection, $cartItemSelectQuery);
            $cartItemSelectRow = mysqli_fetch_assoc($cartItemSelectResult);

            $orderSelectQuery = "SELECT * FROM `order` WHERE user_id_fk = '$userId' AND completion = 0 LIMIT 1";
            $orderSelectResult = mysqli_query($connection, $orderSelectQuery);
            $orderSelectRow = mysqli_fetch_assoc($orderSelectResult);

            $orderId = $orderSelectRow['order_id'];

            $price = $cartItemSelectRow['ci_price'];
            $ciTotal = $quantity * $price;

            $cartItemQuery = "UPDATE cart_item SET ci_quantity = '$quantity', total_price = '$ciTotal' WHERE order_id_fk = '$orderId' AND ci_code = '$code'";
            mysqli_query($connection, $cartItemQuery);

            header("Location: cart.php?actionquantityChange");
            break;
        }
    }
}

include ("includes/header.php");
?>
    <script>setScroll();</script>
    <?php if (isset($_GET['actionproductRemove']) || isset($_GET['actionquantityChange'])): ?>
    <script>getScroll();</script>
    <?php endif ?>
    
<main>
    <div class="heading-cart">
        <h2>Moja košarica</h2>
    </div>
    <div class="heading-small">
        <h2>Pregled proizvoda</h2>
    </div>
    <div class="cart-container">
        <?php

        if (isset($_SESSION["shopping_cart"])) {
            $totalPrice = 0;
            $totalQuantity = 0;
        ?>
            <div class="cart-left-container">
                <div class="cart-products-container">
                    <div class="title-bar-container">
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
                    <?php

                    foreach ($_SESSION["shopping_cart"] as $product) {
                    ?>    
                        <div class="info-bar-container">
                            <div class="product-img-name-remove">
                                <div class="product-img">
                                    <a href="product.php?code=<?php echo $product["code"] . "&ptype=" . $product["ptype"]; ?>"><img src='<?php echo $product["image"]; ?>' alt="Proizvod u kosarici"></a>
                                </div>
                                <div class="product-name-remove">
                                    <div class="product-name">
                                        <a href="product.php?code=<?php echo $product["code"] . "&ptype=" . $product["ptype"]; ?>"><?php echo $product["pname"]; ?></a>
                                    </div>
                                    <div class="product-remove">
                                        <form action="cart.php?actionproductRemove" method="post">
                                            <input type="hidden" name="code" value="<?php echo $product["code"]; ?>" />
                                            <input type="hidden" name="maxquantity" value="<?php echo $product["maxquantity"]; ?>" />
                                            <input type="hidden" name="action" value="remove" />
                                            <button type="submit" class="remove-btn">UKLONI</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="product-price">
                                <?php echo number_format($product["price"], 2, ',', ' ') . " kn"; ?>
                            </div>
                            <div class="product-quantity">
                                <form action="cart.php?actionquantityChange" method="post">
                                    <input type="hidden" name="code" value="<?php echo $product["code"]; ?>" />
                                    <input type="hidden" name="maxquantity" value="<?php echo $product["maxquantity"]; ?>" />
                                    <input type="hidden" name="action" value="change" />
                                    <select name="quantity" id="<?php echo $hash; ?>" class="quantity" onChange="this.form.submit()">
                                        <?php
                                        $maxQuantity = $product["maxquantity"];

                                        for ($i = 1; $i <= $maxQuantity; $i++) { ?>
                                            <option <?php if ($product["quantity"] == $i) echo "selected"; ?> value=<?php echo $i; ?>><?php echo $i; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select><br>
                                </form>
                            </div>
                            <div class="product-total">
                                <?php
                                
                                echo number_format($product["price"] * (int)$product["quantity"], 2, ',', ' ') . " kn";
                                
                                $totalPrice += $product["price"] * (int)$product["quantity"];
                                $totalQuantity += (int)$product["quantity"];
                                $_SESSION['counter'] = $totalQuantity; 
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="cart-right-container">
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
                                    <?php echo $totalQuantity; ?>
                                </div>
                            </div>
                            <div class="price-overview">
                                <div class="price-title">
                                    Ukupna cijena
                                </div>
                                <div class="price-value">
                                    <?php echo number_format($totalPrice, 2, ',', ' ') . " kn"; ?>
                                </div>
                                <div class="delivery-option">

                                </div>
                                <div class="delivery-price">
                                </div>
                            </div>
                        </div>
                        <div class="subtotal-price">
                            <div class="subtotal">
                                Košarica ukupno*
                            </div>
                            <div class="subtotal-value">
                                <?php
                        
                                $subtotalPrice = $totalPrice + 30;

                                echo number_format($totalPrice, 2, ',', ' ') . " kn";

                                $_SESSION['totalPrice'] = number_format($totalPrice, 2, ',', ' ');
                                $_SESSION['subtotalPrice'] = number_format($subtotalPrice, 2, ',', ' ');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="buy-button">
                    <button onclick='window.location.href="checkout.php?state=delivery&delivery=0&payment=0&final=0"' class="buy-btn">NA PLAĆANJE</button>
                </div>
            </div>
            <div class="cart-message">
                * Ukupna cijena košarice je iznos svih proizvoda u košarici (PDV uračunat u cijenu).
            </div>
        <?php

        } 
        
        else {
            echo "Vaša košarica je prazna.";
        }
        ?>
    </div>
</main>
<?php

include ("includes/footer.php");
?>