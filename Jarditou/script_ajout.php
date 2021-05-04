<?php

// Dans ce fichier, nous récupérons les informations passées dans le fichier "ajout.php" dans la balise <form> et l'attribut action="script_ajout.php" 

// Les informations sont récupéré avec variable superglobale $_POST pour réaliser la requête INSERT

$pro_id = htmlspecialchars($_POST['id']);    // La fonction "htmlspecialchars" nous aide d'éviter la faille XSS
$pro_ref = htmlspecialchars($_POST['ref']);
$pro_cat_id = htmlspecialchars($_POST['cat']);
$pro_libelle = htmlspecialchars($_POST['lib']);
$pro_description = htmlspecialchars($_POST['desc']);
$pro_prix = htmlspecialchars($_POST['price']);
$pro_stock = htmlspecialchars($_POST['stock']);
$pro_couleur = htmlspecialchars($_POST['color']);
$pro_photo = htmlspecialchars($_POST['ext']);
$pro_d_ajout = htmlspecialchars($_POST['add']);
$pro_d_modif = htmlspecialchars($_POST['modif']);
$pro_bloque = htmlspecialchars($_POST['bloq']);

// IMPORTANT : Si il y a erreur de "Undefined index" au lieu d'écrire  $pro_id = $_POST['id']; on peut écrire comme suite par exemple:

// if (isset($_POST['id'])) 
// {
//     $pro_id = $_POST['id'];
// }

// if (isset($_POST['ref'])) 
// {
//     $pro_ref = $_POST['ref'];
// }
// etc...........



/* Le problème principal de l'upload d'un fichier est la sécurité. On doit tout d'abord vérifier 2 points basiques :
   1. Le fichier a-t-il bien été téléchargé ?
   2. Le type du fichier envoyé par l'utilisateur est-il celui attendu (image, document Word, PDF etc...) ?
*/

// PHP fournit un extension nommée FILE_INFO qui fait référence en termes de sécurité. Voici comment l'utiliser, pour un type :
// On met les types autorisés dans un tableau (ici pour une image):
$aMimeTypes = array("image/gif", "image/jpeg", "image/pjpeg", "image/jpg", "image/png", "image/x-png", "image/tiff");

// On extrait le type du fichier via l'extension FILE_INFO 
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimetype = finfo_file($finfo, $_FILES["fichier"]["tmp_name"]);
finfo_close($finfo);

if (in_array($mimetype, $aMimeTypes))
{
   /* Le type est parmi ceux autorisés, donc OK, on va pouvoir déplacer et renommer le fichier.
   Par défaut, le fichier téléchargé est stocké dans le répertoire tmp (temporary) de notre serveur Wamp dans C:/wamp/tmp 
   Mais ce fichier devra se trouver dans un répertoire de notre projet, il faut donc le déplacer.
   Donc, via la méthode "move_uploded_file()" on va déplacer notre fichier image vers le répertoire "image" de notre projet et 
   au même temps on rénomme l'image en lui donnant le nom "$pro_id" (id de produit)  et l'extension du fichier "$pro_photo" */   
   move_uploaded_file($_FILES["fichier"]["tmp_name"], "public/image/$pro_id.$pro_photo");   
} 
else 
{
   // Le type n'est pas autorisé, donc ERREUR
   echo "Type de fichier non autorisé";    
   exit;
}    


// Dans votre projet, vous devez bien sûr remplacer photo.jpg par le nom de fichier souhaité, c'est-à-dire le pro_id et 
// l'extesnion du fichier téléchargé. Le code suivant vous permettra d'obtenir l'extension :
$extension = substr(strrchr($_FILES["fichier"]["name"], "."), 1);


// Connection à la base de données 
require "connection_bdd.php";


//Construction de la requête INSERT sans injection SQL
$requete = $db->prepare("INSERT INTO produits (pro_id, pro_ref, pro_cat_id, pro_libelle, pro_description, pro_prix, pro_stock,  
pro_couleur, pro_photo, pro_d_ajout, pro_d_modif, pro_bloque) VALUES (:pro_id, :pro_ref, :pro_cat_id, :pro_libelle, :pro_description, 
:pro_prix, :pro_stock, :pro_couleur, :pro_photo, :pro_d_ajout, :pro_d_modif, :pro_bloque)");


// Association des valeurs aux marqueurs via méthode "bindValue()"
$requete->bindValue(':pro_id', $pro_id, PDO::PARAM_INT);
$requete->bindValue(':pro_ref', $pro_ref, PDO::PARAM_STR);
$requete->bindValue(':pro_cat_id', $pro_cat_id, PDO::PARAM_INT);
$requete->bindValue(':pro_libelle', $pro_libelle, PDO::PARAM_STR);
$requete->bindValue(':pro_description', $pro_description, PDO::PARAM_STR);
$requete->bindValue(':pro_prix', doubleval($pro_prix), PDO::PARAM_STR);  //fonction doubleval() convertit le type de variable en décimale
$requete->bindValue(':pro_stock', $pro_stock, PDO::PARAM_INT);
$requete->bindValue(':pro_couleur', $pro_couleur, PDO::PARAM_STR);
$requete->bindValue(':pro_photo', $pro_photo, PDO::PARAM_STR);

$time = new DateTime();   // On utilise l'objet DateTime() pour enregistrer la date et l'heure d'ajout ou modification du produit dans la base de données
$date = $time->format("Y/m/d H:i:s"); 

$requete->bindValue(':pro_d_ajout', $date, PDO::PARAM_STR);
$requete->bindValue(':pro_d_modif', $date, PDO::PARAM_STR);

$requete->bindValue(':pro_bloque', $pro_bloque, PDO::PARAM_INT);


// Exécution de la requête
$requete->execute();


//Libèration la connection au serveur de BDD
$requete->closeCursor();


//Redirection vers la page index.php 
header("Location: index.php");

exit;

?>

