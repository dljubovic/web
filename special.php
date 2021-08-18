<?php

include ("includes/functions.php");
addToCart ($connection);
include ("includes/header.php");
?>
    <script>setScroll();</script>
    <?php if (isset($_GET['actionaddProduct']) || isset($_GET['orderby'])): ?>
    <script>getScroll();</script>
    <?php endif ?>
    <main>
        <div class="heading-special">
            <h2>Posebna ponuda</h2>
        </div>
        <div class="heading-small">
            <h2>Cjelokupni asortiman</h2>
        </div>
        <div class="filter-order-container">
            <div class="filter-order-title">
                <div class="order">
                    <span class="order-title">POREDAJ PO</span><br><br>
                    <div class="order-forms">
                        <form action="<?php echo "?"; if (isset($_GET['filter'])) { echo "filter=" . $_GET['filter'] . "&"; } echo "orderby="; if (isset($_GET['orderby']) && $_GET['orderby'] == "nameASC") { echo ""; } else { echo "nameASC"; } ?>" method="post">     
                            <button class="order-button <?php if (isset($_GET['orderby']) && $_GET['orderby'] == "nameASC") { echo "order-active"; } ?>">NAZIV A-Z</button>
                        </form>
                        <form action="<?php echo "?"; if (isset($_GET['filter'])) { echo "filter=" . $_GET['filter'] . "&"; } echo "orderby="; if (isset($_GET['orderby']) && $_GET['orderby'] == "nameDESC") { echo ""; } else { echo "nameDESC"; } ?>" method="post">
                            <button class="order-button <?php if (isset($_GET['orderby']) && $_GET['orderby'] == "nameDESC") { echo "order-active"; } ?>">NAZIV Z-A</button>
                        </form>
                        <form action="<?php echo "?"; if (isset($_GET['filter'])) { echo "filter=" . $_GET['filter'] . "&"; } echo "orderby="; if (isset($_GET['orderby']) && $_GET['orderby'] == "priceASC") { echo ""; } else { echo "priceASC"; } ?>" method="post">     
                            <button class="order-button <?php if (isset($_GET['orderby']) && $_GET['orderby'] == "priceASC") { echo "order-active"; } ?>">CIJENA MANJA-VEĆA</button>
                        </form>
                        <form action="<?php echo "?"; if (isset($_GET['filter'])) { echo "filter=" . $_GET['filter'] . "&"; } echo "orderby="; if (isset($_GET['orderby']) && $_GET['orderby'] == "priceDESC") { echo ""; } else { echo "priceDESC"; } ?>" method="post">     
                            <button class="order-button <?php if (isset($_GET['orderby']) && $_GET['orderby'] == "priceDESC") { echo "order-active"; } ?>">CIJENA VEĆA-MANJA</button>
                        </form>
                    </div>    
                </div>
            </div>
        </div>      
        <div class="product-container-grid">
            <?php 
            displayProducts ($connection);
            ?>
        </div>
    </main>
<?php

include ("includes/footer.php");
?>