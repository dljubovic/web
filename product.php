<?php

include ("includes/functions.php");
addToCart ($connection);
include ("includes/header.php");
?>
    <script>setScroll();</script>
    <?php if (isset($_GET['actionaddProduct'])): ?>
    <script>getScroll();</script>
    <?php endif ?>
    <main> 
        <?php
        $cheeseScale = "";
        $code = $_GET['code'];
        $button = "add-product-btn";
        $heading = "heading";
        $allSpecs = "";
        $cheeseIcon = "";
        $spec = array(
            "type",
            "flavour",
            "food",
            "year",
            "temperature",
            "origin"
        );

        if (checkType() == "special") {
            $heading .= " heading-special";
        }

        if (checkType() == "oprema") {
            $heading .= " heading-eq";
        }

        if (checkType() == "sir") {
            $heading .= " heading-cheese";
            $button = "add-product-btn btn-cheese";
            $cheeseScale = "style='transform: scale(0.75)'";
        }

        $productQuery = "SELECT * FROM product WHERE code = '$code' LIMIT 1";
        $productResult = mysqli_query($connection, $productQuery);
        $productRow = mysqli_fetch_assoc($productResult);
        
        for ($i = 0; $i <= 5; $i++) {
            
            if (!empty($productRow[$spec[$i]])) {

                if ($i == 0 || $i == 2 ) {

                    if (checkType() == "sir") {
                        $cheeseIcon = "-cheese";
                    }
                }
                
                else {
                    $cheeseIcon = "";
                }
                
                $allSpecs .= "
                    <div class=\"$spec[$i]\">
                        <img src=\"images/$spec[$i]". $cheeseIcon .".png\" width=\"20px\" alt=\"Ikona\">
                    </div>
                    <div class=\"$spec[$i]_text\">
                        <text>" . $productRow[$spec[$i]] . "</text>
                    </div>
                    ";
            }
        }

        $page = $_SERVER['REQUEST_URI'];
        $pageAction = "&actionaddProduct";

        if (strpos($page, 'actionaddProduct') !== false) {
            $pageAction = "";
        }

        
        if (isset($_SESSION["shopping_cart"])) {

            $array_keys = array_keys($_SESSION["shopping_cart"]);

        }

        if (isset($_SESSION["shopping_cart"]) && in_array($code, $array_keys)) {


        echo "
        <div class=\"$heading\">
            <h2>Detalji proizvoda</h2>
        </div>
        <form action=\"$page$pageAction\" method=\"post\">
            <input type=\"hidden\" name=\"code\" value=" . $productRow['code'] . ">
            <input type=\"hidden\" name=\"maxquantity\" value=" . $productRow['maxquantity'] . ">
            <div class=\"heading-small\">
                <h2>" . $productRow['pname'] . "</h2>
            </div>
            <div class=\"product-details-container-grid\">
                <div class=\"product-image-container\">
                    <img $cheeseScale src=" . $productRow['image'] . " height=\"80%\" alt=\"Product image\">
                </div>
                <div class=\"specs-container\">
                    $allSpecs
                </div>
                <div class=\"description-container\">
                    <div class=\"desc-heading\">
                        Opis
                    </div>
                    <div class=\"desc-text\"><br>
                        <div class=\"product-code\"><b>Šifra proizvoda: " . $productRow['code'] . "</b></div>
                        <p>
                            <text>
                                " . $productRow['description'] . "
                            </text>
                        </p>
                    </div>
                </div>
                <div class=\"product-button-container\">
                    <div class=\"btn-heading\">
                        Cijena
                    </div>
                    <div class=\"price\">
                        " . number_format($productRow['price'], 2, ',', ' ') . " kn 
                    </div>
                    <div class=\"product-add\">    
                        <button type=\"submit\" class=\"product-in-cart-view\" name=\"add\">&#10004; U KOŠARICI</button>
                    </div>
                </div>
            </div>
        </form>
        ";

        }

        else {
        
        echo "
            <div class=\"$heading\">
                <h2>Detalji proizvoda</h2>
            </div>
            <form action=\"$page$pageAction\" method=\"post\">
                <input type=\"hidden\" name=\"code\" value=" . $productRow['code'] . ">
                <input type=\"hidden\" name=\"maxquantity\" value=" . $productRow['maxquantity'] . ">
                <div class=\"heading-small\">
                    <h2>" . $productRow['pname'] . "</h2>
                </div>
                <div class=\"product-details-container-grid\">
                    <div class=\"product-image-container\">
                        <img $cheeseScale src=" . $productRow['image'] . " height=\"80%\" alt=\"Product image\">
                    </div>
                    <div class=\"specs-container\">
                        $allSpecs
                    </div>
                    <div class=\"description-container\">
                        <div class=\"desc-heading\">
                            Opis
                        </div>
                        <div class=\"desc-text\"><br>
                            <div class=\"product-code\"><b>Šifra proizvoda: " . $productRow['code'] . "</b></div>
                            <p>  
                                <text>
                                    " . $productRow['description'] . "
                                </text>
                            </p>
                        </div>
                    </div>
                    <div class=\"product-button-container\">
                        <div class=\"btn-heading\">
                            Cijena
                        </div>
                        <div class=\"price\">
                            " . number_format($productRow['price'], 2, ',', ' ') . " kn 
                        </div>
                        <div class=\"product-add\">
                            <button class=\"$button\" type=\"submit\" name=\"add\">DODAJ U KOŠARICU</button>
                        </div>
                    </div>
                </div>
            </form>
            ";
        }
        mysqli_close($connection);
        ?>
    </main>
<?php

include ("includes/footer.php");
?>