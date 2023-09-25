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

// Bestellung abschließen
if (isset($_POST['submit_order']) && !empty($_SESSION['warenkorb'])) {
    $address = $_POST['address'];
    $sql_insert_order = "INSERT INTO `ordering` (address) VALUES ('$address')";
    if (mysqli_query($conn, $sql_insert_order)) {
        $last_order_id = mysqli_insert_id($conn);
        foreach ($_SESSION['warenkorb'] as $product_id => $quantity) {
            for ($i = 0; $i < $quantity; $i++) {
                $sql_insert_ordered_article = "INSERT INTO `ordered_articles` (f_article_id, f_order_id) VALUES ($product_id, $last_order_id)";
                mysqli_query($conn, $sql_insert_ordered_article);
            }
        }
        unset($_SESSION['warenkorb']);  // Leere den Warenkorb nach Abschluss der Bestellung
        echo "<p>Bestellung erfolgreich abgeschlossen!</p>";
    }
}
?>

<main>
    <h2>Unsere Speisekarte</h2>
    <div class="speisekarte">
        <?php
        // Abfrage der Pizzen aus der Datenbank
        $sql = "SELECT * FROM article";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="pizza">';
                echo '<img src="' . $row['picture'] . '" alt="' . $row['name'] . '" style="width: 200px; height: auto; max-height: 200px; object-fit: cover; border-radius: 10px;">';
                echo '<h3>' . $row['name'] . '</h3>';
                echo '<p>Preis: ' . $row['price'] . '€</p>';
                echo '<form action="speisekarte.php" method="post">';
                echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                echo '<button type="submit" name="add_to_cart">In den Warenkorb</button>';
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
    <div class="warenkorb">
        <?php
        if (isset($_SESSION['warenkorb']) && !empty($_SESSION['warenkorb'])) {
            foreach ($_SESSION['warenkorb'] as $product_id => $quantity) {
                $sql_product = "SELECT * FROM article WHERE id = $product_id";
                $product_result = mysqli_query($conn, $sql_product);
                $product = mysqli_fetch_assoc($product_result);
                echo '<div>';
                echo $product['name'] . ' x' . $quantity;
                echo '<form action="speisekarte.php" method="post">';
                echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                echo '<button type="submit" name="remove_from_cart">Entfernen</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "<p>Ihr Warenkorb ist leer.</p>";
        }
        ?>
    </div>

    <!-- Adresse und Bestellung abschließen -->
    <h2>Bestellung abschließen</h2>
    <form action="speisekarte.php" method="post">
        <label for="address">Adresse:</label>
        <textarea name="address" required></textarea>
        <input type="submit" name="submit_order" value="Bestellung abschließen">
    </form>

</main>

<?php
include 'footer.php';
?>
