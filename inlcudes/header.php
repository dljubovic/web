<!DOCTYPE html>
<html>

<head>
    <script>
        
    if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
    }

    function setScroll () {
        window.onbeforeunload = function(e) {
            localStorage.setItem('scrollpos', window.scrollY);
        };
    }

    function getScroll () {
        
        document.addEventListener("DOMContentLoaded", function(event) { 
            var scrollpos = localStorage.getItem('scrollpos');
            if (scrollpos) window.scrollTo(0, scrollpos);
        });
    }
    </script>
    <meta charset="utf-8">
    <title>
        <?php

        if (basename($_SERVER['PHP_SELF']) == 'index.php') {
            echo "Početna - House of Wine";
        } else if (basename($_SERVER['PHP_SELF']) == 'podrum.php') {
            echo "Vinski podrum - House of Wine";
        } else if (basename($_SERVER['PHP_SELF']) == 'oprema.php') {
            echo "Oprema - House of Wine";
        } else if (basename($_SERVER['PHP_SELF']) == 'special.php') {
            echo "Posebna ponuda - House of Wine";
        } else if (basename($_SERVER['PHP_SELF']) == 'sirevi.php') {
            echo "Ponuda sireva - House of Wine";
        } else if (basename($_SERVER['PHP_SELF']) == 'product.php') {
            echo "Detalji proizvoda - House of Wine";
        } else if (basename($_SERVER['PHP_SELF']) == 'acc.php') {
            echo "Moj račun - House of Wine";
        } else if (basename($_SERVER['PHP_SELF']) == 'login.php') {
            echo "Prijava - House of Wine";
        } else if (basename($_SERVER['PHP_SELF']) == 'register.php') {
            echo "Registracija - House of Wine";
        } else if (basename($_SERVER['PHP_SELF']) == 'cart.php') {
            echo "Košarica - House of Wine";
        } else if (basename($_SERVER['PHP_SELF']) == 'checkout.php') {
            echo "Završetak kupnje - House of Wine";
        } else echo "House of Wine";
        ?>
    </title>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="images/HoW_Logo_Alt.png">
</head>

<body>
    <?php if (basename($_SERVER['PHP_SELF']) !== 'success.php') : ?>
    <header>
        <div class="navbar-container">
            <div class="logo">
                <a href="index.php">
                    <?php if (basename($_SERVER['PHP_SELF']) == 'sirevi.php' || checkType() == "sir") { ?>
                        <div class="nowine">
                            <div class="logo-change">Cheese</div>
                        </div>
                    <?php } ?>
                    <img src="images/HoW_Logo4.png" alt="House of Wine" height="58">
                </a>
            </div>
            <div class="alt-logo">
                <a href="index.php"><img src="images/HoW_Logo_Alt.png" alt="House of Wine Alt" height="60"></a>
            </div>
            <a href="#" class="menu-button">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </a>
            <nav class="nav-links">
                <h2>Izbornik
                    <hr>
                </h2>
                <ul>
                    <li <?php if (basename($_SERVER['PHP_SELF']) == 'podrum.php' || checkType() == "vino") {
                            echo "id=\"active-link\"";
                        } ?>><a href="podrum.php">Vinski podrum</a>
                    </li>
                    <li <?php if (basename($_SERVER['PHP_SELF']) == 'oprema.php' || checkType() == "oprema") {
                            echo "id=\"active-link\"";
                        } ?>><a href="oprema.php">Oprema</a>
                    </li>
                    <li <?php if (basename($_SERVER['PHP_SELF']) == 'special.php' || checkType() == "special") {
                            echo "id=\"active-link\"";
                        } ?>><a href="special.php">Posebna ponuda</a>
                    </li>
                    <li <?php if (basename($_SERVER['PHP_SELF']) == 'sirevi.php' || checkType() == "sir") {
                            echo "id=\"active-link\"";
                        } ?>><a href="sirevi.php">Izbor sireva</a>
                    </li>
                    <li <?php if (basename($_SERVER['PHP_SELF']) == 'info.php') {
                            echo "id=\"active-link\"";
                        } ?>><a href="info.php">O nama</a>
                    </li>
                </ul>
            </nav>
            <div>
                <?php

                cartItems();
                ?>
                <a href="cart.php">
                    <svg class="cart <?php if (basename($_SERVER['PHP_SELF']) == 'cart.php') {
                                            echo "icon-active";
                                        } ?>" id="Layer_3" enable-background="new 0 0 64 64" viewBox="0 0 64 64" width="20pt" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path d="m25 29h-4c-.552 0-1 .447-1 1v8.332c-1.82.898-3 2.758-3 4.83v12.838c0 1.654 1.346 3 3 3h6c1.654 0 3-1.346 3-3v-12.838c0-2.072-1.18-3.932-3-4.83v-8.332c0-.553-.448-1-1-1zm-1 2v2h-2v-2zm3 14v6h-8v-6zm-1 12h-6c-.551 0-1-.448-1-1v-3h8v3c0 .552-.449 1-1 1zm.987-14h-7.974c.067-1.392.97-2.606 2.304-3.052.408-.136.683-.517.683-.948v-4h2v4c0 .431.275.812.684.948 1.333.446 2.236 1.66 2.303 3.052z" />
                            <path d="m61.39 9.024 1.317-1.317-1.414-1.414-2.995 2.995c-.002.002-.003.003-.005.005l-1.331 1.331c-.193-2.028-1.885-3.624-3.962-3.624-.624 0-1.207.156-1.734.412.299-1.722.847-3.402 1.628-4.964l-1.789-.895c-.678 1.357-1.198 2.794-1.552 4.271-.304-.751-.757-1.441-1.347-2.031-1.156-1.156-2.693-1.793-4.327-1.793h-.879c-.552 0-1 .447-1 1v.879c0 1.636.637 3.173 1.793 4.329.064.064.137.117.204.177-.534.613-.878 1.384-.959 2.238l-1.328-1.327c-.001-.001-.002-.002-.003-.003l-3-3-1.414 1.414 1.317 1.317c-.112.08-.219.17-.318.269-.94.941-.94 2.473 0 3.414.912.912 2.503.912 3.415 0 .1-.1.188-.206.267-.318l.818.818c-1.613.515-2.792 2.011-2.792 3.793h-5v-4c0-6.617-5.383-12-12-12s-12 5.383-12 12v4h-9c-.552 0-1 .447-1 1v44c0 .553.448 1 1 1h42c.552 0 1-.447 1-1v-33h-2v32h-40v-42h8v2.142c-1.72.447-3 1.999-3 3.858 0 2.206 1.794 4 4 4s4-1.794 4-4c0-1.859-1.28-3.411-3-3.858v-2.142h20v2.142c-1.72.447-3 1.999-3 3.858 0 2.206 1.794 4 4 4s4-1.794 4-4c0-1.859-1.28-3.411-3-3.858v-2.142h5.556c.626 1.074 1.729 1.82 3.023 1.957-.359.6-.579 1.294-.579 2.043 0 2.062 1.574 3.744 3.579 3.957-.359.6-.579 1.294-.579 2.043 0 2.206 1.794 4 4 4s4-1.794 4-4c0-.749-.22-1.443-.579-2.043 2.005-.213 3.579-1.895 3.579-3.957 0-.749-.22-1.443-.579-2.043 2.005-.213 3.579-1.895 3.579-3.957 0-1.782-1.179-3.278-2.792-3.793l.818-.818c.079.112.167.219.267.318.912.912 2.503.912 3.415 0 .94-.941.94-2.473 0-3.414-.1-.099-.206-.189-.318-.269zm-47.39 15.976c0 1.103-.897 2-2 2s-2-.897-2-2c0-.737.405-1.375 1-1.722v1.722h2v-1.722c.595.347 1 .985 1 1.722zm22 0c0 1.103-.897 2-2 2s-2-.897-2-2c0-.737.405-1.375 1-1.722v1.722h2v-1.722c.595.347 1 .985 1 1.722zm-23-8v-4c0-5.514 4.486-10 10-10s10 4.486 10 10v4zm34-8c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2zm3 10c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2zm3-10c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2zm-6.207-3.793c.53.53.882 1.183 1.063 1.89-.276-.06-.562-.097-.856-.097-.447 0-.87.091-1.272.227-.184-.131-.36-.273-.521-.434-.75-.749-1.175-1.736-1.205-2.791 1.041.032 2.052.467 2.791 1.205zm-4.793 11.793c0-1.103.897-2 2-2s2 .897 2 2-.897 2-2 2-2-.897-2-2zm3 6c0-1.103.897-2 2-2s2 .897 2 2-.897 2-2 2-2-.897-2-2zm5 8c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2zm3-6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2zm5-8c0 1.103-.897 2-2 2s-2-.897-2-2 .897-2 2-2 2 .897 2 2z" />
                        </g>
                    </svg></a>
                <a href="acc.php">
                    <svg class="account <?php if (basename($_SERVER['PHP_SELF']) == 'account.php') {
                                            echo "icon-active";
                                        } ?>" height="20pt" viewBox="0 0 480 480" width="20pt" xmlns="http://www.w3.org/2000/svg">
                        <path d="m240 0c-132.546875 0-240 107.453125-240 240s107.453125 240 240 240c7.230469 0 14.433594-.324219 21.601562-.96875 6.664063-.597656 13.269532-1.511719 19.824219-2.65625l2.519531-.445312c121.863282-22.742188 206.359376-134.550782 194.960938-257.996094-11.398438-123.445313-114.9375-217.8945315-238.90625-217.933594zm-19.28125 463.152344h-.566406c-6.222656-.550782-12.398438-1.382813-18.519532-2.449219-.351562-.0625-.703124-.101563-1.046874-.167969-5.984376-1.070312-11.90625-2.398437-17.769532-3.949218l-1.417968-.363282c-5.71875-1.550781-11.375-3.351562-16.949219-5.351562-.578125-.207032-1.160157-.390625-1.738281-.605469-5.464844-2.007813-10.832032-4.257813-16.117188-6.691406-.65625-.292969-1.3125-.574219-1.96875-.886719-5.183594-2.398438-10.265625-5.101562-15.25-7.945312-.703125-.398438-1.414062-.796876-2.117188-1.191407-4.90625-2.863281-9.699218-5.933593-14.402343-9.175781-.710938-.496094-1.429688-.976562-2.136719-1.472656-4.621094-3.277344-9.125-6.757813-13.511719-10.398438l-1.207031-1.054687v-67.449219c.058594-48.578125 39.421875-87.941406 88-88h112c48.578125.058594 87.941406 39.421875 88 88v67.457031l-1.0625.886719c-4.472656 3.734375-9.0625 7.265625-13.777344 10.601562-.625.4375-1.257812.855469-1.878906 1.285157-4.757812 3.304687-9.632812 6.414062-14.625 9.335937-.625.363282-1.265625.707032-1.886719 1.066406-5.058593 2.878907-10.203125 5.597657-15.449219 8.046876-.601562.28125-1.207031.542968-1.816406.800781-5.328125 2.457031-10.742187 4.71875-16.246094 6.742187-.546874.203125-1.097656.378906-1.601562.570313-5.601562 2.007812-11.28125 3.824219-17.03125 5.382812l-1.378906.34375c-5.871094 1.550781-11.796875 2.886719-17.789063 3.960938-.34375.0625-.6875.105469-1.03125.160156-6.128906 1.070313-12.3125 1.902344-18.539062 2.457031h-.566407c-6.398437.550782-12.800781.847656-19.28125.847656-6.480468 0-12.933593-.242187-19.320312-.792968zm179.28125-66.527344v-52.625c-.066406-57.410156-46.589844-103.933594-104-104h-112c-57.410156.066406-103.933594 46.589844-104 104v52.617188c-86.164062-87.941407-85.203125-228.9375 2.148438-315.699219 87.351562-86.757813 228.351562-86.757813 315.703124 0 87.351563 86.761719 88.3125 227.757812 2.148438 315.699219zm0 0" />
                        <path d="m240 64c-44.183594 0-80 35.816406-80 80s35.816406 80 80 80 80-35.816406 80-80c-.046875-44.164062-35.835938-79.953125-80-80zm0 144c-35.347656 0-64-28.652344-64-64s28.652344-64 64-64 64 28.652344 64 64c-.039062 35.328125-28.671875 63.960938-64 64zm0 0" />
                    </svg></a>
            </div>
        </div>
    </header>
    <?php endif ?>