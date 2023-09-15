<?php
include "config.php";  // Initialisiert die Session und lädt allgemeine Einstellungen

// Artikel zum Warenkorb hinzufügen
if (isset($_GET['artikel']) && isset($_GET['action']) && $_GET['action'] == 'add') {
    $artikelName = $_GET['artikel'];
    $preis = floatval($_GET['preis']);

    $artikel = array('name' => $artikelName, 'preis' => $preis);
    $_SESSION['warenkorb'][] = $artikel;  // Artikel wird zum Warenkorb-Array hinzugefügt
}

// Artikel aus dem Warenkorb entfernen
if (isset($_GET['artikelIndex']) && isset($_GET['action']) && $_GET['action'] == 'remove') {
    $index = $_GET['artikelIndex'];
    if (isset($_SESSION['warenkorb'][$index])) {
        unset($_SESSION['warenkorb'][$index]);
        $_SESSION['warenkorb'] = array_values($_SESSION['warenkorb']); // Re-index the array after unsetting
    }
}

// Gesamtpreis berechnen
$gesamtpreis = 0;
foreach ($_SESSION['warenkorb'] as $artikel) {
    $gesamtpreis += $artikel['preis'];
}

include "header.php";  // Lädt den Header
?>

<main>
    <h2>Speisekarte</h2>

    <h3>Pizzen:</h3>
    <ul>
        <li><a href="speisekarte.php?artikel=Margherita&preis=7.00&action=add">Margherita - 7,00 €</a></li>
        <li><a href="speisekarte.php?artikel=Salami&preis=8.50&action=add">Salami - 8,50 €</a></li>
        <li><a href="speisekarte.php?artikel=Funghi&preis=8.00&action=add">Funghi - 8,00 €</a></li>
    </ul>

    <h3>Pasta:</h3>
    <ul>
        <li><a href="speisekarte.php?artikel=Spaghetti Carbonara&preis=9.00&action=add">Spaghetti Carbonara - 9,00 €</a></li>
        <li><a href="speisekarte.php?artikel=Lasagne&preis=10.00&action=add">Lasagne - 10,00 €</a></li>
    </ul>

    <h3>Getränke:</h3>
    <ul>
        <li><a href="speisekarte.php?artikel=Cola&preis=2.50&action=add">Cola - 2,50 €</a></li>
        <li><a href="speisekarte.php?artikel=Wasser&preis=2.00&action=add">Wasser - 2,00 €</a></li>
    </ul>

    <h3>Warenkorb:</h3>
    <ul>
        <?php
        foreach ($_SESSION['warenkorb'] as $index => $artikel) {
            echo "<li>" . $artikel['name'] . " - " . $artikel['preis'] . " € <a href='speisekarte.php?artikelIndex=$index&action=remove'>Entfernen</a></li>";
        }
        ?>
    </ul>
    <p>Gesamtpreis: <?php echo number_format($gesamtpreis, 2, ',', '.'); ?> €</p>
</main>

<?php
include "footer.php";  // Lädt den Footer
?>
