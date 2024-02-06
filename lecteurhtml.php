<?php
// lecteurhtml.php

if (isset($_GET['fichier'])) {
    $fichier = $_GET['fichier'];

    // Supprimer les espaces du nom de fichier
    $fichier = str_replace(' ', '', $fichier);

    // Vérifier que le fichier existe
    $chemin_absolu = $_SERVER['DOCUMENT_ROOT'] . '/' . str_replace('/', DIRECTORY_SEPARATOR, $fichier);

    if (file_exists($chemin_absolu)) {
        // Charger et afficher le contenu du fichier HTML
        $contenu = file_get_contents($chemin_absolu);
        echo $contenu;
    } else {
        echo "Le fichier n'existe pas. Chemin du fichier : " . $chemin_absolu;
    }
} else {
    echo "Aucun fichier spécifié.";
}
?>
