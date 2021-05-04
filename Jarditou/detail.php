<?php 
    session_start();  // il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans 
                      // lequel on manipulera cette variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout 
                      // echo ou quoi que ce soit d'autre : rien ne doit avoir encore été écrit/envoyé à la page web.


    if (isset($_SESSION['login']))
    {
        echo 'Bonjour '. $_SESSION['login'] ;
    }
    else
    {
        echo "<h4> Cette page nécessite une identification </h4>";
        header("refresh:2; url=login.php");  // refresh:2 signifie que après 5 secondes l'utilisateur sera redirigé sur la page login.php. 
        exit;
    }
?>



<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">

        <!-- Responsive web design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap CSS 4.5.3 import from CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" 
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <title> Détail </title>
    </head>


    <!-- PAGE HEAD -->
    <?php
        if (file_exists("header.php"))
        {
            include("header.php");
        }
        else
        {
            echo "file 'header.php' n'existe pas";
        }
    ?>


    <!-- Code PHP -->
    <?php

    //Récupération de l'identifiant "pro_id" passé en GET dans le fichier "index.php": <a href="detail.php?pro_id=<?php echo $row->pro_id">
    $pro_id=$_GET['pro_id'];

    // Connection à la base de données 
    require "connection_bdd.php";

    $requete = "SELECT * FROM produits WHERE pro_id=".$pro_id;
    $result = $db->query($requete);

    //Si la requête renvoit un seul et unique résultat, on ne fait pas de boucle, contrairement à la page index.php !
    //ici c'est le cas, récupération du résultat de la requête

    $row = $result->fetch(PDO::FETCH_OBJ);

    //libère la connection au serveur de BDD
    $result->closeCursor(); 

    ?>


    <!-- PAGE MAIN CONTENT -->
    <div class="container"> 
        <form  action="modif.php"  method="GET"  style="padding-left: 100px">
            <label for="image"> </label> <br>
            <img src="<?php echo "public/image/"; echo $row->pro_id; echo "."; echo $row->pro_photo ?>"  alt="imgproduit"  class="img-fluid"  
            style="width:600px; padding-left:300px">
            <br><br>

            <label for="reference"> Réference : </label> <br>
            <input type="text"  name="ref_produit"  id="reference"  value=<?php echo $row->pro_ref?>  style="width:90%"  disabled>
            <br><br>

            <label for="categorie"> Catégorie : </label> <br>
            <input type="text"  name="categorie_produit"  id="categorie"  value=<?php echo $row->pro_cat_id?>  style="width:90%"  readonly>
            <br><br>

            <label for="libelle"> Libellé : </label> <br>
            <input type="text"  name="libelle_produit"  id="libelle"  value=<?php echo $row->pro_libelle?>  style="width:90%"  disabled>
            <br><br>

            <label for="description"> Description : </label> <br>
            <input type="text"  name="description_produit"  id="description"  value=<?php echo $row->pro_description?>  style="width:90%"  readonly>
            <br><br>

            <label for="prix"> Prix : </label> <br>
            <input type="text"  name="prix_produit"  id="prix"  value=<?php echo $row->pro_prix?>  style="width:90%"  disabled>
            <br><br>

            <label for="stock"> Stock : </label> <br>
            <input type="text"  name="stock_produit"  id="stock"  value=<?php echo $row->pro_stock?>  style="width:90%"  disabled>
            <br><br>

            <label for="couleur"> Couleur : </label> <br>
            <input type="text"  name="couleur_produit"  id="couleur"  value=<?php echo $row->pro_couleur?>  style="width:90%"  disabled>
            <br><br>

            <label for="bloque"> Produit bloqué ? : </label> <br>
            <input type="radio"  name="bloque_produit"  id="bloque"  value=<?php echo $row->pro_bloque?>  disabled  checked> Oui
            <input type="radio"  name="bloque_produit"  id="bloque"  value=<?php echo $row->pro_bloque?>  disabled> Non
            <br><br>

            <label for="ajout"> Date d'ajout : </label> <br>
            <input type="text"  name="ajout_produit"  id="ajout"  value=<?php echo $row->pro_d_ajout?>  style="width:90%"  disabled>
            <br><br>

            <label for="modif"> Date de modification : </label> <br>
            <input type="date"  name="modif_produit"  id="modif"  value=<?php echo $row->pro_d_modif?>  style="width:90%"  disabled>
            <br><br>
        </form>


        <!--  Les boutons  RETOUR,  MODIFIER, SUPPRIMER et DECONNEXION  -->
        <div style="margin: 20px 0 20px 200px">
            <a href="index.php"> 
                <button style="margin-left:40px; padding:10px 30px; border-radius:10px; background-color:grey; color:white"> Retour </button> 
            </a> 
            
            <a href="modif.php?pro_id=<?php echo $row->pro_id ?>"> 
                <button style="margin-left:50px; padding:10px 20px; border-radius:10px; background-color:orange; color:0000"> Modifier </button> 
            </a>
            
            <!-- Pour voir le code de la fonction "Suppression" regardez tout en bas de la page -->
            <a href="delete.php?pro_id=<?php echo $row->pro_id ?>">  
                <button style="margin-left:50px; padding:10px 15px; border-radius:10px; background-color:red; color:white"> Supprimer </button> 
            </a> 

            <a href="script_deconnexion.php"> 
                <button style="margin-left:50px; padding:10px 5px; border-radius:10px; background-color:blue; color:white"> Déconnexion </button> 
            </a> 
        </div>
    </div>


    <!-- PAGE FOOT -->
    <?php
        include("footer.php")
    ?>


</html>



