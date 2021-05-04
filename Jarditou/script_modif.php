<?php 

// Dans ce fichier, nous récupérons les informations passées dans le fichier "modif.php" dans la balise <form> et 
// l'attribut action="script_modif.php" 

// Les informations sont récupéré avec variable superglobale $_POST pour réaliser la requête de modification: UPDATE

$pro_id = htmlspecialchars($_POST['id']);   // La fonction "htmlspecialchars" nous aide d'éviter la faille XSS
$pro_ref = htmlspecialchars($_POST['ref']);
$pro_cat_id = htmlspecialchars($_POST['cat']);
$pro_libelle = htmlspecialchars($_POST['lib']);
$pro_description = htmlspecialchars($_POST['desc']);
$pro_prix = htmlspecialchars($_POST['price']);
$pro_stock = htmlspecialchars($_POST['stock']);
$pro_couleur = htmlspecialchars($_POST['color']);
$pro_photo = htmlspecialchars($_POST['ext']);
$pro_bloque = htmlspecialchars($_POST['bloq']);
$pro_cat_id = htmlspecialchars($_POST['categ']);


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


// Connection à la base de données 
require "connection_bdd.php";

// Construction de la requête UPDATE 
$requete = $db->prepare("UPDATE  produits  SET  pro_ref=:pro_ref,  pro_cat_id=:pro_cat_id,  pro_libelle=:pro_libelle, 
pro_description=:pro_description,  pro_prix=:pro_prix,  pro_stock=:pro_stock,  pro_couleur=:pro_couleur,  pro_photo=:pro_photo, 
pro_bloque=:pro_bloque,  pro_cat_id=:pro_cat_id,  pro_d_modif=:pro_d_modif  WHERE  pro_id=:pro_id");


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
$requete->bindValue(':pro_bloque', $pro_bloque, PDO::PARAM_STR);
$requete->bindValue(':pro_cat_id', $pro_cat_id, PDO::PARAM_STR);

$time = new DateTime();   // On utilise l'objet DateTime() pour enregistrer la date et l'heure de modification du produit dans la base de données
$date = $time->format("Y/m/d H:i:s"); 

$requete->bindValue(':pro_d_modif', $date, PDO::PARAM_STR);


// Exécution de la requête
$requete->execute();

//Libère la connection au serveur de BDD
$requete->closeCursor();

//Redirection vers la page index.php 
header("Location: index.php");

exit;

?>

