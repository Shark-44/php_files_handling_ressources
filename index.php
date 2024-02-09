<?php include('inc/head.php'); ?>

<?php
// initialisation du repertoire
$dir = "./files";

// Supprimer un dossier ou un fichier
function deleteElement($element) {
    echo '<a href="?delete_element=' . urlencode($element) . '"><img src="assets/images/delete.png" alt="Supprimer" style="width: 16px;"></a>';
}

// Afficher les éléments dans le répertoire principal
if (!isset($_GET['d'])) {
    if (is_dir($dir)){
        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                if (!in_array($file, array(".", ".."))) {
                    echo '<img src="assets/images/dossier.png" alt="dossier" style="width: 30px">'," ","<a href='?d=" . "'>$file </a>";
                    deleteElement($file);
                    echo "<br>";
                }
            }
            closedir($dh);  
        }
    }
}

// Afficher les fichiers dans un sous-dossier sélectionné
if (isset($_GET['d'])) {
    $sous_dossier = "./files/" . $_GET['d'];
    $dir_sous_dossier = opendir($sous_dossier);
    $chemins = array();

    while ($elementb = readdir($dir_sous_dossier)) {
        if (!in_array($elementb, array(".", ".."))) {
            $chemin_elementb = "$sous_dossier/$elementb";
            $chemins[] = $chemin_elementb;
            
            if (is_dir($chemin_elementb)) {
                $nouveau_sous_dossier = $_GET['d'] . '/' . $elementb;
                echo '<img src="assets/images/dossier.png" alt="dossier" style="width: 30px">'," ","<a href='?d=" . urlencode($nouveau_sous_dossier) . "'>$elementb </a>";
                deleteElement($elementb);
                echo "<br>";
            } else {
                echo '<img src="assets/images/fichier.png" alt="fichier" style="width: 30px">'," ","<a href='?f=" . urlencode($chemin_elementb) . "'>$elementb</a>";
                deleteElement($chemin_elementb);
                echo "<br>";
            }
        }
    }

    closedir($dir_sous_dossier);
}

// Supprimer un dossier ou un fichier 
if (isset($_GET['delete_element']) && !empty($_GET['delete_element'])) {
    $element_a_supprimer = $_GET['delete_element'];

    // Obtenez le chemin du dossier parent du fichier actuel
    $dossier_parent = dirname($_SERVER["DOCUMENT_ROOT"] . $_SERVER['PHP_SELF']);
    
    // Chemin du sous-dossier
    $chemin = ''; // Vous devriez remplir cela avec la valeur correcte

    // Construisez le chemin complet pour le dossier ou le fichier à supprimer
    $chemin_element_a_supprimer = $dossier_parent . "/files" . $chemin . "/" . $element_a_supprimer;

    echo "Tentative de suppression de : " . $chemin_element_a_supprimer; // Ajout de cette ligne pour déboguer

    if (is_dir($chemin_element_a_supprimer)) {
        rmdir($chemin_element_a_supprimer);
        echo "Le dossier a été supprimé avec succès.";
    } elseif (is_file($chemin_element_a_supprimer)) {
        unlink($chemin_element_a_supprimer);
        echo "Le fichier a été supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression de l'élément.";
    }
}


// fonction pour savoir si un dossier enfant d'un dossier enfant
function isChildFolder($parentFolder, $childFolder)
{
    $parentFolder = rtrim($parentFolder, '/') . '/';

    // Utilise realpath pour résoudre les liens symboliques et obtenir des chemins absolus
    $realParent = realpath($parentFolder);
    $realChild = realpath($parentFolder . $childFolder);

    // Vérifiez si le dossier enfant commence par le dossier parent
    return $realParent !== false && $realChild !== false && strpos($realChild, $realParent) === 0;
}


// Fonction récursive pour supprimer un dossier et son contenu
function RemoveAll($element_a_supprimer) {
    $items = scandir($element_a_supprimer);
    echo $element_a_supprimer;

    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..') {
            $path = $element_a_supprimer . '/' . $item;
            if (is_dir($path)) {
                RemoveAll($path);
            } elseif (is_file($path)) {
                unlink($path);
            }
        }
    }

    rmdir($element_a_supprimer);
}

?>

  <?php
      // Afficher un lien pour remonter au dossier parent
      if (isset($_GET['d']) && strlen($_GET['d']) > 0){
        $dossier_parent = realpath($sous_dossier . '/..');
        if ($sous_dossier !== "./files" && $dossier_parent !== false && file_exists($dossier_parent)) {
            $dossier_parent_relative = str_replace(realpath("./files"), "", $dossier_parent);
            echo "<br><a href='?d=" . urlencode($dossier_parent_relative) . "'>Remonter au dossier parent</a>";
        }
    }
  ?>
<?php
$contenu = '';

// Chargement du fichier dans textarea
if (isset($_GET["f"])) {
    $fichier = urldecode($_GET["f"]);

    if (is_file($fichier)) {
        $contenu = file_get_contents($fichier);
    } else {
        echo "Le fichier n'existe pas.";
        echo $fichier;
    }
}
// Execution du submit
if (isset($_POST["contenu"]) && !empty($_POST["contenu"])) {
    $fichier = urldecode($_POST["file"]);
   
    $file = fopen($fichier, "w");

    if ($file) {
        fwrite($file, $_POST["contenu"]);
        fclose($file);
    } else {
        echo "Erreur lors de l'ouverture du fichier.";
    }
}
?>



<h4>Editeur</h4>
<form method='POST' action='index.php'>
    <textarea name='contenu' style='width:100%;height:200px'><?php echo $contenu; ?></textarea>
    <input type='hidden' name='file' value='<?php echo $fichier; ?>'>
    <input type='submit' value='envoyer'>
</form>




<?php include('inc/foot.php'); ?>
