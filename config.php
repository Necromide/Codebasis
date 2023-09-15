<?php
// Session wird gestartet
session_start();

// Warenkorb-Initialisierung (wenn er noch nicht existiert)
if (!isset($_SESSION['warenkorb'])) {
    $_SESSION['warenkorb'] = array();
}

// Weitere allgemeine Einstellungen oder Funktionen können hier hinzugefügt werden
?>
