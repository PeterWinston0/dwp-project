<?php
require '../includes/config.php';
require "../includes/layout/frontHeader.php";
if (isset($_POST['search'])) {
?>

<div class="page-container">
    <div class="page-title">
        <h1>Search Results</h1>
    </div>
    <div class="product-container" style="">
        <?php
    $keyword = $_POST['keyword'];
    $query = $db->prepare("SELECT * FROM `product` WHERE `title` COLLATE UTF8_GENERAL_CI LIKE '%$keyword%'");
    $query->execute();
    while ($row = $query->fetch()) {
            ?>

        <!-- <?php echo $row['title'] ?> -->

        <div class="product">
            <a class="myLink" href="single-product.php?product=<?php echo $row['id'] ?>">
                <img class="" src="../crud/products/img/<?= $row['image'] ?>" alt="Card image cap">
                <div class="body">
                    <h5 class="title">
                        <?= $row['title'] ?>
                    </h5>
                    <p class="text">
                        DKK <?= $row['price'] ?>,-
                    </p>
                    <!-- <p class="text">
                        <?= $row['description'] ?>
                    </p> -->
                </div>
            </a>
        </div>


        <?php
    }
            ?>
    </div>
</div>
<?php
} else {
?>
<p> Your search didnt find anything </p>

<?php
}
?>

<?php require("../includes/layout/frontFooter.php") ?>