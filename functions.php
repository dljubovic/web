<?php 

include ("dbconnect.php");

//incijalizacija pomocnih varijabli
$error = "";
$validMail = true;
$passwordEncrypted = "";
//inicijalizacija varijabli za unos korisnika
$email = "";
$password = "";
$confirmPassword = "";
$fname = "";
$lname = "";
$contact = "";
$adress = "";
$city = "";
$zip = "";

if (isset($_POST['register'])) {

    //postavljanje vrijednosti varijabli koje unosi korisnik
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $contact = $_POST['contact'];
    $adress = $_POST['adress'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];

    //razne provjere tocnosti unosa podataka
    if (empty($email)) {
        $error .= "emailEmpty";
        $validMail = false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= "emailInvalid";
        $validMail = false;
    }

    if (empty($password)) {
        $error .= "passwordEmpty";
    }

    if (strlen(trim($password)) < 6) {
        $error .= "passwordInvalid";
    }

    if ($password != $confirmPassword) {
        $error .= "passwordMismatch";
    }

    if (empty($fname)) {
        $error .= "fnameEmpty";
    }

    if (empty($lname)) {
        $error .= "lnameEmpty";
    }

    if (empty($contact)) {
        $error .= "contactEmpty";
    }

    if (empty($adress)) {
        $error .= "adressEmpty";
    }

    if (empty($city)) {
        $error .= "cityEmpty";
    }

    if (empty($zip)) {
        $error .= "zipEmpty";
    }

    if ($validMail === true) {
        //varijable koje sluze za provjeru da li korsinik sa unesenim e-mailom vec postoji u bazi
        $userQuery = "SELECT email FROM user WHERE email = '$email' LIMIT 1";
        $userResult = mysqli_query($connection, $userQuery);
        $userRow = mysqli_fetch_assoc($userResult);

        if ($userRow) {

            $error .= "emailExisting";
        }
    }

    if (empty($error)) {

        $passwordEncrypted = password_hash($password, PASSWORD_DEFAULT);
        $sqlQuery = "INSERT INTO user (email, password, fname, lname, contact, adress, city, zip) VALUES ('$email', '$passwordEncrypted', '$fname', '$lname', '$contact', '$adress', '$city', '$zip')";

        mysqli_query($connection, $sqlQuery);

        $userQuery = "SELECT * FROM user WHERE email='$email' ";
        $userResult = mysqli_query($connection, $userQuery);
        $userRow = mysqli_fetch_assoc($userResult);

        $_SESSION['loggedIn'] = true;
        $_SESSION['userId'] = $userRow['user_id'];
        $_SESSION['email'] = $userRow['email'];
        $_SESSION['fname'] = $userRow['fname'];
        $_SESSION['lname'] = $userRow['lname'];
        $_SESSION['contact'] = $userRow['contact'];
        $_SESSION['adress'] = $userRow['adress'];
        $_SESSION['city'] = $userRow['city'];
        $_SESSION['zip'] = $userRow['zip'];
        $_SESSION['success'] = "Uspješno ste se registrirali i prijavili.";

        if (isset($_SESSION["shopping_cart"])) {
            $userSelectQuery = "SELECT user_id FROM user WHERE email = '$email' LIMIT 1";
            $userSelectResult = mysqli_query($connection, $userSelectQuery);
            $userSelectRow = mysqli_fetch_assoc($userSelectResult);

            $_SESSION['userId'] = $userSelectRow['user_id'];
            
            $userId = $_SESSION['userId'];
            $guestId = $_SESSION['guestId'];

            $orderUpdateQuery = "UPDATE order SET user_id_fk = '$userId' WHERE user_id_fk = '$guestId'";
            mysqli_query($connection, $orderUpdateQuery);

            $guestDeleteQuery = "DELETE FROM user WHERE user_id = '$guestId'";
            mysqli_query($connection, $orderUpdateQuery);
        }
        header ('Location: index.php');
    }
}

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
        $error .= "emailEmpty";
    }

    if (empty($password)) {
        $error .= "passwordEmpty";
    }

    $passwordQuery = "SELECT password FROM user WHERE email='$email' ";
    $passwordResult = mysqli_query($connection, $passwordQuery);
    $passwordRow = mysqli_fetch_assoc($passwordResult);

    if (!$passwordRow) {
        $error .= "emailWrong";
    }

    if (empty($error) && mysqli_num_rows($passwordResult) === 1) {
        $passwordEncrypted = $passwordRow['password'];

        if (password_verify($password, $passwordEncrypted)) {

            $userQuery = "SELECT * FROM user WHERE email='$email' AND password='$passwordEncrypted' ";
            $userResult = mysqli_query($connection, $userQuery);
            $userRow = mysqli_fetch_assoc($userResult);

            if (mysqli_num_rows($userResult) === 1) {
                $_SESSION['loggedIn'] = true;
                $_SESSION['userId'] = $userRow['user_id'];
                $_SESSION['email'] = $userRow['email'];
                $_SESSION['fname'] = $userRow['fname'];
                $_SESSION['lname'] = $userRow['lname'];
                $_SESSION['contact'] = $userRow['contact'];
                $_SESSION['adress'] = $userRow['adress'];
                $_SESSION['city'] = $userRow['city'];
                $_SESSION['zip'] = $userRow['zip'];

                $orderSelectQuery = "SELECT user_id_fk FROM `order` WHERE user_id_fk = '$userId' AND completion = 0 LIMIT 1";
                $orderSelectResult = mysqli_query($connection, $orderSelectQuery);
                $orderSelectRow = mysqli_fetch_assoc($orderSelectResult);
            
                if ($orderSelectRow) {
                    if ($_SESSION['userId'] == $orderSelectRow['user_id_fk'] && $orderSelectRow['completion'] == 0) {
                        
                    }
                }
        
                if (isset($_SESSION["guestId"])) {

                    $userId = $_SESSION['userId'];        
                    $guestId = $_SESSION['guestId'];

                    $orderInsertQuery = "UPDATE `order` SET user_id_fk = '$userId' WHERE user_id_fk = '$guestId'";
                    mysqli_query($connection, $orderInsertQuery);

                    $guestDeleteQuery = "DELETE FROM user WHERE user_id = '$guestId'";
                    mysqli_query($connection, $guestDeleteQuery);           
                }
                restoreCart();
                header('Location: index.php');
                destroyCookie();
            }
        } 
        
        else {
            $error .= "passwordWrong";
        }
    } 
}

if (isset($_GET['logout'])) {

    if (isset($_SESSION["shopping_cart"])) {
        
        $userId = $_SESSION['userId'];
        $userCart = $_SESSION["shopping_cart"];
        $cartItemsCount = $_SESSION['counter'];

        $cookieName = "userCart" . $userId;
        $counterCookieName = "cartItems" . $userId; 

        setcookie($cookieName, json_encode($userCart), time() + (10 * 365 * 24 * 60 * 60));
        setcookie($counterCookieName, json_encode($cartItemsCount), time() + (10 * 365 * 24 * 60 * 60));

    }
    session_destroy();
    unset($_SESSION['loggedIn']);
    header('Location: index.php');
}

$orderError = "";
$orderFname = "";
$orderFname = "";
$orderLname = "";
$orderEmail = "";
$orderContact = "";
$orderAdress = "";
$orderCity = "";
$orderZip = "";
$validOrderEmail = true;
$sucess = "";

if (isset($_SESSION['loggedIn'])) {
    $orderFname = $_SESSION['fname'];
    $orderLname = $_SESSION['lname'];
    $orderEmail = $_SESSION['email'];
    $orderContact = $_SESSION['contact'];

    if (isset($_GET['delivery']) && $_GET['delivery'] == "yes") {
        $orderAdress = $_SESSION['adress'];
        $orderCity = $_SESSION['city'];
        $orderZip = $_SESSION['zip'];
    }
}

if (isset($_POST['confirm'])) {
  
    $orderFname = $_POST['fname'];
    $orderLname = $_POST['lname'];
    $orderEmail = $_POST['email'];
    $orderContact = $_POST['contact'];

    if ($_GET['delivery'] == "yes") {

        $orderAdress = $_POST['adress'];
        $orderCity = $_POST['city'];
        $orderZip = $_POST['zip'];
    }

    if ($_GET['delivery'] == "yes") {
        $delivery = "Dostava na adresu (30 kn)";
    }

    else if ($_GET['delivery'] == "no") {
        $delivery = "Osobno ću pokupiti";
    }

    if ($_GET['payment'] == "cash") {
        $payment = "Gotovina (pouzećem)";
    }

    else if ($_GET['payment'] == "card") {
        $payment = "Virmansko";
    }

    if (isset($_SESSION['loggedIn'])) {
        $userId = $_SESSION['userId'];
    }

    else if (!isset($_SESSION['loggedIn'])) {
        $userId = $_SESSION['guestId'];
    }

    if (empty($orderFname)) {
        $orderError .= "fnameEmpty";
    }

    if (empty($orderLname)) {
        $orderError .= "lnameEmpty";
    }

    if (empty($orderEmail)) {
        $orderError .= "emailEmpty";
        $validOrderEmail = false;
    }

    else if (!filter_var($orderEmail, FILTER_VALIDATE_EMAIL)) {
        $orderError .= "emailInvalid";
        $validOrderEmail = false;
    }

    if (empty($orderContact)) {
        $orderError .= "contactEmpty";
    }
    
    if ($_GET['delivery'] == "yes") {

        if (empty($orderAdress)) {
            $orderError .= "adressEmpty";
        }

        if (empty($orderCity)) {
            $orderError .= "cityEmpty";
        }

        if (empty($orderZip)) {
            $orderError .= "zipEmpty";
        }
    }

    if (empty($orderError)) {

        $user = $orderFname . " " . $orderLname;
        $contact = $orderEmail . " | " . $orderContact;

        if ($_GET['delivery'] == "yes") {
            $adress = $orderAdress . ", " . $orderCity . ", " . $orderZip;
        }

        else {
            $adress = "-";
        }

        $orderQuantity = $_SESSION['counter'];

        if (deliveryOption() === true || $_GET['delivery'] == "yes") {
            $subtotalPrice = $_SESSION['subtotalPrice'];
        } 

        else {
            $subtotalPrice = $_SESSION['totalPrice'];
        }
        
        $orderInsertQuery = "UPDATE `order` SET order_user = '$user', order_adress = '$adress', order_contact = '$contact', delivery = '$delivery', payment = '$payment', order_quantity = '$orderQuantity', subtotal = '$subtotalPrice', completion = 1 WHERE user_id_fk = '$userId' AND completion = 0";
        mysqli_query($connection, $orderInsertQuery);

        header ("Location: success.php");
    } 
}

$errorText = "";
$successText = "";
if (isset($_POST['confirm_changes'])) {
    
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $adress = $_POST['adress'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $errorText = "GREŠKE PRI UNOSU:<br>";
    
    if (empty($fname)) {
        $error .= "fnameEmpty";
        $errorText .= "<b> IME </b>(prazno polje)";
        $fname = $_SESSION['fname'];
    }

    if (empty($lname)) {
        $error .= "lnameEmpty";
        $errorText .= "<b> PREZIME </b>(prazno polje)";
        $lname = $_SESSION['lname'];
    }

    if (empty($email)) {
        $error .= "emailEmpty";
        $validMail = false;
        $errorText .= "<b> E-MAIL </b>(Prazno polje)";
        $email = $_SESSION['email'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
        $error .= "emailInvalid";
        $validMail = false;
        $errorText .= "<b> E-MAIL: " . $email . "</b> (neispravan e-mail)";
        $email = $_SESSION['email'];
    }

    if (empty($contact)) {
        $error .= "contactEmpty";
        $errorText .= "<b> KONTAKT </b>(prazno polje)";
        $contact = $_SESSION['contact'];
    }

    if (empty($adress)) {
        $error .= "adressEmpty";
        $errorText .= "<b> ADRESA </b>(prazno polje)";
        $adress = $_SESSION['adress'];
    }

    if (empty($city)) {
        $error .= "cityEmpty";
        $errorText .= "<b> GRAD </b>(prazno polje)";
        $city = $_SESSION['city'];
    }

    if (empty($zip)) {
        $error .= "zipEmpty";
        $errorText .= "<b> POŠTANSKI BROJ </b>(prazno polje)";
        $zip = $_SESSION['zip'];
    }

    if ($validMail === true) {
        //varijable koje sluze za provjeru da li korsinik sa unesenim e-mailom vec postoji u bazi
        $userQuery = "SELECT email FROM user WHERE email = '$email' LIMIT 1";
        $userResult = mysqli_query($connection, $userQuery);
        $userRow = mysqli_fetch_assoc($userResult);

        if ($userRow) {
            if ($userRow['email'] === $email && $email !== $_SESSION['email']) {
                $error .= "emailExisting";
                $errorText .= "<b> E-MAIL: " . $email . " </b>(korisnik s ovim e-mailom je već registriran)";
            }
        }
    }    

    $errorText .= " <br><br><b><text style='color: rgb(129, 129, 129)'>Navedeni podaci su vraćeni na prethodne vrijednosti, a ostali su uspješno izmijenjeni.</text></b>";

    if (empty($error)) {
        $errorText = "";
        $successText = "Svi podaci su uspješno izmijenjeni.";
    }

    $userId = $_SESSION['userId'];
    $userUpdateQuery = "UPDATE user SET fname = '$fname', lname = '$lname', email = '$email', contact = '$contact', adress = '$adress', city = '$city', zip = '$zip' WHERE user_id = '$userId'";

    mysqli_query($connection, $userUpdateQuery);

    $_SESSION['fname'] = $fname;
    $_SESSION['lname'] = $lname;
    $_SESSION['email'] = $email;
    $_SESSION['contact'] = $contact;
    $_SESSION['adress'] = $adress;
    $_SESSION['city'] = $city;
    $_SESSION['zip'] = $zip;
}

function filterValueWhite () {
    
    $filter = "white";

    if (isset($_GET['filter'])) { 
        if (strpos($_GET['filter'], 'white') !== false) { 
            $filter = ""; 
        } 
        
        if (strpos($_GET['filter'], 'red') !== false) {
            $filter .= "red"; 
        } 
        
        if (strpos($_GET['filter'], 'other') !== false) {
            $filter .= "other"; 
        } 
    } 

    return $filter;
}

function filterValueRed () {
    
    $filter = "red";

    if (isset($_GET['filter'])) { 
        if (strpos($_GET['filter'], 'red') !== false) { 
            $filter = ""; 
        } 
        
        if (strpos($_GET['filter'], 'white') !== false) {
            $filter .= "white"; 
        } 
        
        if (strpos($_GET['filter'], 'other') !== false) {
            $filter .= "other"; 
        } 
    } 

    return $filter;
}

function filterValueOther () {
    
    $filter = "other";

    if (isset($_GET['filter'])) { 
        if (strpos($_GET['filter'], 'other') !== false) { 
            $filter = ""; 
        } 
        
        if (strpos($_GET['filter'], 'white') !== false) {
            $filter .= "white"; 
        } 
        
        if (strpos($_GET['filter'], 'red') !== false) {
            $filter .= "red"; 
        } 
    } 

    return $filter;
}

function destroyCookie () {

    $userId = $_SESSION['userId'];
    $userCart = "";
    if (isset($_SESSION["shopping_cart"])) {
        $userCart = $_SESSION["shopping_cart"];
    }
    $cartItemsCount = $_SESSION['counter'];
    
    $cookieName = "userCart" . $userId;
    $counterCookieName = "cartItems" . $userId;
    
    setcookie($cookieName, json_encode($userCart), time() - (10 * 365 * 24 * 60 * 60));
    unset($_COOKIE[$cookieName]);

    setcookie($counterCookieName, json_encode($cartItemsCount), time() - (10 * 365 * 24 * 60 * 60));
    unset($_COOKIE[$counterCookieName]);
}

function restoreCart () {

    $userId = $_SESSION['userId'];
    $cookieName = "userCart" . $userId;
    $counterCookieName = "cartItems" . $userId;

    $cartRestore = json_decode($_COOKIE[$cookieName], true);
    $counterRestore = json_decode($_COOKIE[$counterCookieName], true);
    
    if (!empty($cartRestore)) {
        $_SESSION["shopping_cart"] = $cartRestore;
        $_SESSION['counter'] = $counterRestore;
    }
}

function addToCart ($connection) {
    
    if (isset($_POST['code']) && $_POST['code'] != "") {
        $code = $_POST['code'];
        $productQuery = "SELECT * FROM product WHERE code = '$code' ";
        $productResult = mysqli_query($connection, $productQuery);
        $productRow = mysqli_fetch_assoc($productResult);

        $pname = $productRow['pname'];
        $code = $productRow['code'];
        $ptype = $productRow['ptype'];
        $price = $productRow['price'];
        $maxquantity = $productRow['maxquantity'];
        $image = $productRow['image'];

        $cartArray = array(
            $code => array(
                'pname' => $pname,
                'code' => $code,
                'ptype' => $ptype,
                'price' => $price,
                'maxquantity' => $maxquantity,
                'quantity' => 1,
                'image' => $image
            )
        );

        if (empty($_SESSION["shopping_cart"])) {

            $_SESSION["shopping_cart"] = $cartArray;
            $_SESSION['counter']++;
            $status = "Proizvod je dodan u košaricu.";
           
            if (isset($_SESSION['loggedIn'])) {

                $userId = $_SESSION['userId'];
            }
    
            else if (!isset($_SESSION['loggedIn'])) {
    
                $randGuestId = rand (10000000000000000, 99999999999999999);
                $guestName = "Guest" . "#" . $randGuestId;
    
                $userQuery = "INSERT INTO user (fname) VALUES ('$guestName')";
                mysqli_query($connection, $userQuery);
    
                $userSelectQuery = "SELECT user_id FROM user WHERE fname = '$guestName' LIMIT 1";
                $userSelectResult = mysqli_query($connection, $userSelectQuery);
                $userSelectRow = mysqli_fetch_assoc($userSelectResult);
    
                $userId = $userSelectRow['user_id'];
                $_SESSION['guestId'] = $userId;
            }

            $randCode = rand (10000000, 99999999);
            $orderCode = "#" . $randCode;

            $orderQuery = "INSERT INTO `order` (user_id_fk, order_code, completion) VALUES ('$userId', '$orderCode', 0)";
            mysqli_query($connection, $orderQuery);
                    
            $orderSelectQuery = "SELECT order_id FROM `order` WHERE user_id_fk = '$userId' AND completion = 0 LIMIT 1";
            $orderSelectResult = mysqli_query($connection, $orderSelectQuery);
            $orderSelectRow = mysqli_fetch_assoc($orderSelectResult);
        
            $orderId = $orderSelectRow['order_id'];
            $ciQuantity = 1;
            $ciTotal = $ciQuantity * $price;

            $cartItemSelectQuery = "SELECT * FROM cart_item WHERE order_id_fk = '$orderCode' AND ci_code = '$code' LIMIT 1";
            $cartItemSelectResult = mysqli_query($connection, $cartItemSelectQuery);
            $cartItemSelectRow = mysqli_fetch_assoc($cartItemSelectResult);

            if (!$cartItemSelectRow) {

                $cartItemQuery = "INSERT INTO cart_item (order_id_fk, ci_code, ci_name, ci_price, ci_quantity, total_price) VALUES ('$orderId', '$code', '$pname', '$price', $ciQuantity, $ciTotal)";
                mysqli_query($connection, $cartItemQuery);
            }
        } 
        
        else {

            $array_keys = array_keys($_SESSION["shopping_cart"]);
            
            if (in_array($code, $array_keys)) {
                //$status = "Proizvod je već u košarici.";
            } 
            
            else {

                $_SESSION["shopping_cart"] = array_merge(
                $_SESSION["shopping_cart"],
                $cartArray
                );
                $_SESSION['counter']++;

                if (isset($_SESSION['loggedIn'])) {
                    $userId = $_SESSION['userId'];
                }

                else if (!isset($_SESSION['loggedIn'])) {
                    $userId = $_SESSION['guestId'];
                }

                $orderSelectQuery = "SELECT order_id FROM `order` WHERE user_id_fk = '$userId' AND completion = 0 LIMIT 1";
                $orderSelectResult = mysqli_query($connection, $orderSelectQuery);
                $orderSelectRow = mysqli_fetch_assoc($orderSelectResult);

                $orderId = $orderSelectRow['order_id'];
                $ciQuantity = 1;
                $ciTotal = $ciQuantity * $price;

                $cartItemQuery = "INSERT INTO cart_item (order_id_fk, ci_code, ci_name, ci_price, ci_quantity, total_price) VALUES ('$orderId', '$code', '$pname', '$price', $ciQuantity, $ciTotal)";
                mysqli_query($connection, $cartItemQuery);
                
                //$status = "Proizvod je dodan u košaricu.";
            }
        }
    }
}

function displayProducts ($connection) {
    
    $ptype = "vino";
    $button = "cart-btn";

    if (basename($_SERVER['PHP_SELF']) == 'special.php') {
        $ptype = "special";
    }

    if (basename($_SERVER['PHP_SELF']) == 'oprema.php') {
        $ptype = "oprema";
    }

    if (basename($_SERVER['PHP_SELF']) == 'sirevi.php') {
        $ptype = "sir";
        $button = "cart-btn-cheese";
    
    }

    $page = $_SERVER['REQUEST_URI'];
    if (strpos($page, '?') !== false) {
        $pageAction = "&actionaddProduct";
    }

    else {
        $pageAction = "?actionaddProduct";
    }

    if (strpos($page, 'actionaddProduct') !== false) {
        $pageAction = "";
    }

    $productQuery = "SELECT * FROM product WHERE ptype LIKE '%$ptype%'";
    $filters = 0;

    if (isset($_GET['filter'])) {

        if ($_GET['filter'] == "") {
            $filters = 0;
        }

        if (strpos($_GET['filter'], 'white') !== false) {
            if ($filters == 0) {
                $productQuery .= " AND ptype LIKE '%bijelo%'";
                $filters = 1;
            }

            else {
                $productQuery .= " OR ptype LIKE '%bijelo%'";
            }
            
        }

        if (strpos($_GET['filter'], 'red') !== false) {
            if ($filters == 0) {
                $productQuery .= " AND ptype LIKE '%crno%'";
                $filters = 1;
            }

            else {
                $productQuery .= " OR ptype LIKE '%crno%'";
            }
        }

        if (strpos($_GET['filter'], 'other') !== false) {
            if ($filters == 0) {
                $productQuery .= " AND ptype LIKE '%rose%' OR ptype LIKE '%pjenušavo%'";
                $filters = 1;
            }

            else {
                $productQuery .= " OR ptype LIKE '%rose%' OR ptype LIKE '%pjenušavo%'";
            }
        }
    }

    if (isset($_GET['orderby'])) {

        if ($_GET['orderby'] == "nameASC") {
            $productQuery .= " ORDER BY pname ASC";
        }

        else if ($_GET['orderby'] == "nameDESC") {
            $productQuery .= " ORDER BY pname DESC";
        }

        else if ($_GET['orderby'] == "priceASC") {
            $productQuery .= " ORDER BY price ASC";
        }

        else if ($_GET['orderby'] == "priceDESC") {
            $productQuery .= " ORDER BY price DESC";
        }
    }

    $productResult = mysqli_query($connection, $productQuery);

    while ($productRow = mysqli_fetch_assoc($productResult)) {
        $code = $productRow['code'];
        $ptype = $productRow['ptype'];

        if (isset($_SESSION["shopping_cart"])) {

            $array_keys = array_keys($_SESSION["shopping_cart"]);
        }

        if (isset($_SESSION["shopping_cart"]) && in_array($code, $array_keys)) {

            echo " 
                <form action=\"$page$pageAction\" method=\"post\">
                <input type=\"hidden\" name=\"code\" value=" . $productRow['code'] . ">
                <input type=\"hidden\" name=\"maxquantity\" value=" . $productRow['maxquantity'] . ">
                <div>
                    <a href=\"product.php?code=$code&ptype=$ptype\" class=\"product-link\">
                        <img src=" . $productRow['image'] . " alt=\"Proizvod\">
                        <h6>" . $productRow['pname'] . "</h6>
                    </a>
                    <hr>
                    <span>" . number_format($productRow['price'], 2, ',', ' ') . " kn</span>
                    <button type=\"submit\" class=\"product-in-cart\" name=\"add\">&#10004; U KOŠARICI</button>
                </div>        
            </form>
            ";
        }

        else {

        echo " 
            <form action=\"$page$pageAction\" method=\"post\">
                <input type=\"hidden\" name=\"code\" value=" . $productRow['code'] . ">
                <input type=\"hidden\" name=\"maxquantity\" value=" . $productRow['maxquantity'] . ">
                <div>
                    <a href=\"product.php?code=$code&ptype=$ptype\" class=\"product-link\">
                        <img src=" . $productRow['image'] . " alt=\"Proizvod\">
                        <h6>" . $productRow['pname'] . "</h6>
                    </a>
                    <hr>
                    <span>" . number_format($productRow['price'], 2, ',', ' ') . " kn</span>
                    <button type=\"submit\" class=" . $button . " name=\"add\">DODAJ U KOŠARICU</button>
                </div>        
            </form>
            ";
        }
    }
    mysqli_close($connection);
}

function cartItems() {

    $cart_count = 0;
    if (empty($_SESSION["shopping_cart"])) {

        $_SESSION['counter'] = 0;
    } 

    else if (!empty($_SESSION["shopping_cart"])) {

        $cart_count = $_SESSION['counter'];
    }
    echo "<div class=\"cart-count\">" . $cart_count . "</div>";
}

function checkStep () {

    if ($_GET['state'] == "delivery") {
        return "delivery";
    }

    if ($_GET['state'] == "payment") {
        return "payment";
    }
    
    if ($_GET['state'] == "final") {
        return "final";
    }
    else return "0";
}

function checkNext () {

    if ($_GET['state'] == "delivery") {  
        return "payment";             
    }

    if($_GET['state'] == "payment") {
        return "final";
    }
    
    if ($_GET['state'] == "final") {
        return "final";
    }

}

function checkType () {

    if (basename($_SERVER['PHP_SELF']) == 'product.php') {

        if (strpos($_GET['ptype'], 'vino') !== false) {
            return "vino";
        } else if (strpos($_GET['ptype'], 'special') !== false) {
            return "special";
        } else if (strpos($_GET['ptype'], 'oprema') !== false) {
            return "oprema";
        } else if (strpos($_GET['ptype'], 'sir') !== false) {
            return "sir";
        } else {
            return 0;
        }
    }
}

function deliveryOption () {
    
    if (isset($_POST['delivery'])) {

        if ($_POST['delivery'] == "yes" || $_GET['delivery'] == "yes") {
            return true;                                                                                               
        }

        else if ($_POST['delivery'] == "no" || $_GET['delivery'] == "no") {
            return false;
        }
    }
}

function paymentOption () {

    if (isset($_POST['payment'])) {

        if ($_POST['payment'] == "cash" || $_GET['payment'] == "cash") {
            return true;                                                                                               
        }

        else if ($_POST['payment'] == "card" || $_GET['payment'] == "card") {
            return false;
        }
    }
}

function continueButton ()  {
    
    if (checkStep() == "delivery" && deliveryOption() == true) {
        echo "yes";
    }

    else if (checkStep() == "delivery" && deliveryOption() == false) {
        echo "no";
    }

    if (checkStep() == "payment" && paymentOption() == true) {
        echo "cash";
    }

    else if (checkStep() == "payment" && paymentOption() == false) {
        echo "card";
    }
}

function backButton () {

    $delivery = "";

    if (checkStep() == "delivery") {
        echo "cart.php";
    }

    if (checkStep() == "payment") {

        echo "checkout.php?state=delivery&delivery=0&payment=0&back";
    }

    if (checkStep() == "final") {

        if ($_GET['delivery'] == "yes") {
            $delivery = "yes";
        }

        echo "checkout.php?state=payment&delivery=$delivery&payment=0&back";

    }
}

?>