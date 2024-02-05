<?php
include('inc/head.php');

// Vérifier si le paramètre 'dossier' est défini dans l'URL
if (isset($_GET['dossier'])) {
    // Construire le chemin du dossier actuel
    $dossier_actuel = isset($_GET['dossier']) ? $_GET['dossier'] : '';
    $directory = "./files/" . $dossier_actuel;

    // Lister le contenu du dossier
    if ($handle = opendir($directory)) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                $path = $dossier_actuel . '/' . $item;
                if (is_dir($directory . '/' . $item)) {
                    echo "<a href='navigateur.php?dossier=$path'>$item (Dossier)</a> ";
                } else {
                    echo "$item ";
                }
            }
        }
        closedir($handle);

        // Ajouter le lien pour remonter au dossier parent
        if ($dossier_actuel != '') {
            $dossier_parent = dirname($dossier_actuel);
            echo "<br><a href='navigateur.php?dossier=$dossier_parent'>Remonter au dossier parent</a>";
        }
    } else {
        echo "Impossible d'ouvrir le dossier.";
    }
} else {
    echo "Aucun dossier spécifié.";
}

include('inc/foot.php');
?>
