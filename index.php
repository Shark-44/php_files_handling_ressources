<?php
include('inc/head.php');

// Déterminer le chemin du dossier actuel
$directory = isset($_GET['dossier']) ? "./files/" . $_GET['dossier'] : "./files";

// Lister le contenu du dossier
if ($handle = opendir($directory)) {
    while (false !== ($item = readdir($handle))) {
        if ($item != "." && $item != "..") {
            $path = $directory . '/' . $item;
            if (is_dir($path)) {
                echo "<a href='navigateur.php?dossier=$item'>$item </a> ";
            } else {
                echo "$item ";
            }
        }
    }
    closedir($handle);
}

include('inc/foot.php');
?>
