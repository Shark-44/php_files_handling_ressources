<?php include('inc/head.php'); ?>

<?php
function listerFichiers($chemin) {
    $dir = opendir("./files");

    while ($element = readdir($dir)) {
            if (!in_array($element, array(".", ".."))) {
            $chemin_element = "$chemin/$element";
            
            if (is_dir($chemin_element)) {
                echo '<img src="assets/images/dossier.png" alt="dossier" style="width: 30px">'," ","<a href='?d=$element' > $element </a>";
                echo ' <a href="?delete=' . urlencode($chemin_element) . '&type=folder" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce dossier?\')"><img src="assets/images/delete.png" alt="Supprimer" style="width: 16px;"></a>';
                
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

    file_put_contents($fichier, $contenu_modifie);
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
                $nouveau_sous_dossier = $_GET['d'] . '/' . $elementb;
                echo '<img src="assets/images/dossier.png" alt="dossier" style="width: 30px">'," ","<a href='?d=" . urlencode($nouveau_sous_dossier) . "'>$elementb </a>",' <a href="?delete=' . urlencode($elementb) . '&type=folder" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce dossier?\')"><img src="assets/images/delete.png" alt="Supprimer" style="width: 16px;"><br></a>';
                

                if (file_exists($chemin_elementb)) {
                    listerFichiers($chemin_elementb);
                } else {
                    echo "Le dossier $elementb n'existe pas.";
                }
            } else {
                echo '<img src="assets/images/fichier.png" alt="fichier" style="width: 30px">'," ","<a href='?f=" . urlencode($chemin_elementb) . "'>$elementb</a>",' <a href="?delete=' . urlencode($elementb) . '&type=folder" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce fichier?\')"><img src="assets/images/delete.png" alt="Supprimer" style="width: 16px;"></a><br>';
            }
        }
    }

    closedir($dir_sous_dossier);
    // Ajouter la suppression
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $element_to_delete = urldecode($_GET['delete']);
    $delete_type = $_GET['type'];
    echo '<script>alert("je passe dans la fonction supprime")</script>';
    
    $full_path_to_delete = "./files/" . $element_to_delete;

    if ($delete_type === 'folder') {
        if (rmdir($full_path_to_delete)) {
            echo '<script>alert("Dossier supprimé avec succès")</script>';
        } else {
            echo '<script>alert("Erreur lors de la suppression du dossier")</script>';
        }
    } elseif ($delete_type === 'file') {
        if (unlink($full_path_to_delete)) {
            echo '<script>alert("Fichier supprimé avec succès")</script>';
        } else {
            echo '<script>alert("Erreur lors de la suppression du fichier")</script>';
        }
    }

    // Rediriger vers le dossier parent après la suppression
    $redirect_url = isset($_GET['d']) ? '?d=' . urlencode($_GET['d']) : '';
    header("Location: index.php$redirect_url");
    exit();
}


    // Afficher un lien pour remonter au dossier parent
    if (isset($_GET['d']) && strlen($_GET['d']) > 0){
    $dossier_parent = realpath($sous_dossier . '/..');
    if ($sous_dossier !== "./files" && $dossier_parent !== false && file_exists($dossier_parent)) {
        $dossier_parent_relative = str_replace(realpath("./files"), "", $dossier_parent);
        echo "<br><a href='?d=" . urlencode($dossier_parent_relative) . "'>Remonter au dossier parent</a>";
    }
}

} else if (isset($_GET["f"])) {
    $fichier = urldecode($_GET["f"]);
    $contenu = file_get_contents($fichier);

    // Afficher le formulaire pour modifier le contenu du fichier
    echo "<form method='POST' action='index.php'>";
    echo "<textarea name='contenu' style='width:100%;height:200px'>$contenu</textarea>";
    echo "<input type='hidden' name='file' value='$fichier'>";
    echo "<input type='submit' value='envoyer'>";
    echo "</form>";
} else {
    $dir = "./files";
    listerFichiers($dir);
}
?>
<?php include('inc/foot.php'); ?>
