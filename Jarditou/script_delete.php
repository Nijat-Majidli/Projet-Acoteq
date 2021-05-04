<?php

//récupération de l'identifiant passé en GET
$pro_id=$_GET['pro_id'];

//permet de vérifier que l'on a bien l'identifiant attendu
//mettre le header plus bas en commentaire !

//var_dump("id : ".$pro_id);
//echo("<br>");

// Connection à la base de données 
require "connection_bdd.php";


//construction de la requête DELETE sans injection SQL
$requete = $db->prepare("DELETE from produits WHERE pro_id=:pro_id");


// Association des valeurs aux marqueurs via méthode "bindValue()"
$requete->bindValue(':pro_id', $pro_id, PDO::PARAM_INT);

// Exécution de la requête
$requete->execute();

//libère la connection au serveur de BDD
$requete->closeCursor();

//redirection vers index.php
header("Location: index.php");

?>







