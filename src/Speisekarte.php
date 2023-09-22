<?php
include 'config.php';
include "header.php";

// Produkt zum Warenkorb hinzufügen
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['warenkorb'][$product_id])) {
        $_SESSION['warenkorb'][$product_id]++;
    } else {
        $_SESSION['warenkorb'][$product_id] = 1;
    }
}

// Produkt aus dem Warenkorb entfernen
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['warenkorb'][$product_id]);
}
?>

<main>
    <h2>Unsere Speisekarte</h2>
    <div class="speisekarte">

        <?php
        $sql = "SELECT * FROM article";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="pizza">';
                echo '<img src="' . $row['picture'] . '" alt="' . $row['name'] . '" style="width: 200px; height: auto; max-height: 200px; object-fit: cover; border-radius: 10px;">';
                echo '<h3>' . $row['name'] . '</h3>';
                echo '<p>Preis: ' . $row['price'] . '€</p>';

                // Hinzufügen zum Warenkorb
                echo '<form action="speisekarte.php" method="post">';
                echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                echo '<input type="submit" name="add_to_cart" value="In den Warenkorb">';
                echo '</form>';

                echo '</div>';
            }
        } else {
            echo "<p>Derzeit sind keine Pizzen in unserer Speisekarte.</p>";
        }
        ?>

    </div>

    <!-- Warenkorb Anzeige -->
    <h2>Ihr Warenkorb</h2>
    <?php
    if (empty($_SESSION['warenkorb'])) {
        echo "<p>Der Warenkorb ist leer.</p>";
    } else {
        foreach ($_SESSION['warenkorb'] as $product_id => $quantity) {
            $sql = "SELECT * FROM article WHERE id=$product_id";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            echo '<div class="cart-item">';
            echo $row['name'] . " - " . $row['price'] . "€ - Anzahl: " . $quantity;

            // Entfernen aus Warenkorb
            echo '<form action="speisekarte.php" method="post" style="display: inline;">';
            echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
            echo '<input type="submit" name="remove_from_cart" value="Entfernen">';
            echo '</form>';

            echo '</div>';
        }
    }
    ?>

    <a href="bestellen.php">Bestellung abschließen</a>

</main>

<?php
include 'footer.php';
?>
