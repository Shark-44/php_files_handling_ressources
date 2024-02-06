<?php include('inc/head.php'); ?>

<?php
function listerFichiers($chemin) {
    $dir = opendir("./files");

    while ($element = readdir($dir)) {
        if (!in_array($element, array(".", ".."))) {
            $chemin_element = "$chemin/$element";
            
            if (is_dir($chemin_element)) {
                // Si c'est un dossier, créer un lien pour explorer son contenu
                echo "<a href='?d=$element'>$element </a><br>";
                
                // Liste récursive des fichiers dans le sous-dossier
                listerFichiers($chemin_element);
            }
        }
    }

    closedir($dir);
}
// Enregistrer les modifications si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["contenu"]) && isset($_POST["file"])) {
    $fichier = $_POST["file"];
    $contenu_modifie = $_POST["contenu"];

    // Enregistrez le contenu modifié dans le fichier
    file_put_contents($fichier, $contenu_modifie);

    // Afficher un message de succès ou rediriger l'utilisateur vers une autre page si nécessaire
    echo '<script>alert("Enregistré avec succès")</script>';
}

// Afficher les fichiers dans un sous-dossier sélectionné
if (isset($_GET['d'])) {
    $sous_dossier = "./files/" . $_GET['d'];
    $dir_sous_dossier = opendir($sous_dossier);

    while ($elementb = readdir($dir_sous_dossier)) {
        if (!in_array($elementb, array(".", ".."))) {
            $chemin_elementb = "$sous_dossier/$elementb";
            if (is_dir($chemin_elementb)) {
                // Si c'est un dossier, créer un lien pour explorer son contenu
                $nouveau_sous_dossier = $_GET['d'] . '/' . $elementb;
                echo "<a href='?d=" . urlencode($nouveau_sous_dossier) . "'>$elementb (Dossier)</a><br>";
                
                // Liste récursive des fichiers dans le sous-dossier
                if (file_exists($chemin_elementb)) {
                    listerFichiers($chemin_elementb);
                } else {
                    echo "Le dossier $elementb n'existe pas.";
                }
            } else {
                // Si c'est un fichier, créer un lien pour le visualiser
                echo "<a href='?f=" . urlencode($chemin_elementb) . "'>$elementb (Fichier)</a><br>";
            }
        }
    }

    closedir($dir_sous_dossier);

    // Afficher un lien pour remonter au dossier parent
    if (isset($_GET['d']) && strlen($_GET['d']) > 0){
    $dossier_parent = realpath($sous_dossier . '/..');
    if ($sous_dossier !== "./files" && $dossier_parent !== false && file_exists($dossier_parent)) {
        $dossier_parent_relative = str_replace(realpath("./files"), "", $dossier_parent);
        echo "<br><a href='?d=" . urlencode($dossier_parent_relative) . "'>Remonter au dossier parent</a>";
    }
}

} else if (isset($_GET["f"])) {
    // Afficher le contenu d'un fichier sélectionné
    $fichier = urldecode($_GET["f"]);
    $contenu = file_get_contents($fichier);

    // Afficher le formulaire pour modifier le contenu du fichier
    echo "<form method='POST' action='index.php'>";
    echo "<textarea name='contenu' style='width:100%;height:200px'>$contenu</textarea>";
    echo "<input type='hidden' name='file' value='$fichier'>";
    echo "<input type='submit' value='envoyer'>";
    echo "</form>";
} else {
    // Aucun dossier ni fichier sélectionné, afficher le contenu de ./files
    $dir = "./files";
    listerFichiers($dir);
}
?>
<?php include('inc/foot.php'); ?>
