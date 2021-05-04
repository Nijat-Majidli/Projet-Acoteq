<?php 
    session_start();  // il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans 
                      // lequel on manipulera cette variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout 
                      // echo ou quoi que ce soit d'autre : rien ne doit avoir encore été écrit/envoyé à la page web.


    if (isset($_SESSION['role']) && $_SESSION['role']=='admin')
    {
        echo 'Bonjour '. $_SESSION['role'] ." ". $_SESSION['login'] ;
    }
    else
    {
        echo "<h4> Cette page est un espace d'administration </h4>";
        header("refresh:2; url=index.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page index.php. 
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

        <title> Modif </title>
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

    // Récupération de l'identifiant concerné, passé en GET dans le fichier "detail.php": <a href="modif.php?pro_id=<?php echo $row->pro_id"> 
    $pro_id=$_GET['pro_id'];

    // Connection à la base de données 
    require "connection_bdd.php";

    // Construction de la requète
    $requete = "SELECT * FROM produits WHERE pro_id=".$pro_id;
    $result = $db->query($requete);

    //Si la requête renvoit un seul et unique résultat, on ne fait pas de boucle, contrairement à la page index.php !
    $row = $result->fetch(PDO::FETCH_OBJ);

    //Libèration de la connection au serveur de BDD
    $result->closeCursor();
        
    ?>


    <!-- PAGE MAIN CONTENT -->
    <div class="container">
        <form  action="script_modif.php"  method="POST"  style="padding-left: 100px">
            <!-- Image de produit -->
            <label for="image"> </label> <br>
            <img src="<?php echo "public/image/"; echo $row->pro_id; echo "."; echo $row->pro_photo ?>" alt="imgproduit" class="img-fluid" style="width:600px; padding-left:300px">
            <br><br>

            <label for="id_produit"> Produit ID : </label> <br>
            <input type="text"  name="id"  id="id_produit"  value=<?php echo $row->pro_id?>  style="width:90%"  readonly>
            <br><br>

            <label for="reference"> Réference : </label> <br>
            <input type="text"  name="ref"  id="reference"  value=<?php echo $row->pro_ref?>  style="width:90%"  required>
            <br><br>
                
            <label for="libelle"> Libellé : </label> <br>
            <input type="text"  name="lib"  id="libelle"  value=<?php echo $row->pro_libelle?>  style="width:90%"  required>
            <br><br>

            <label for="description"> Description : </label> <br>
            <input type="text"  name="desc"  id="description"  value=<?php echo $row->pro_description?>  style="width:90%">
            <br><br>

            <label for="prix"> Prix : </label> <br>
            <input type="text"  name="price"  id="prix"  value=<?php echo $row->pro_prix?>  style="width:90%"  required>
            <br><br>

            <label for="stock_produit"> Stock : </label> <br>
            <input type="text"  name="stock"  id="stock_produit"  value=<?php echo $row->pro_stock?>  style="width:90%"  required>
            <br><br>

            <label for="couleur"> Couleur : </label> <br>
            <input type="text"  name="color"  id="couleur"  value=<?php echo $row->pro_couleur?>  style="width:90%"  required>
            <br><br>

            <label for="extension"> Extension de la photo : </label> <br>
            <input type="text"  name="ext"  id="extension"  value=<?php echo $row->pro_photo?>  style="width:90%"  required>
            <br><br>

            <label for="bloque"> Produit bloqué ? : </label> <br>
            <input type="radio"  name="bloq"  id="bloque"  value=<?php echo $row->pro_bloque?>  checked> Oui
            <input type="radio"  name="bloq"  id="bloque"  value=<?php echo $row->pro_bloque?> > Non
            <br><br>

            <label for="ajout"> Date d'ajout : </label> <br>
            <input type="text"  name="date_add"  id="ajout"  value=<?php echo $row->pro_d_ajout?>  style="width:90%"  disabled>
            <br><br>

            <label for="modification"> Date de modification : </label> <br>
            <input type="date"  name="date_modif"  id="modification"  value=<?php echo $row->pro_d_modif?>  style="width:90%"  disabled>
            <br><br>


            <label for="categorie"> Catégorie : </label> <br>
                <select id="categorie"  name="categ"  style="width:90%"  required> 
                        <!-- Code PHP -->
                        <?php
                        //Connéxion à la base de données 
                        require "connection_bdd.php";
                        
                        //Sélectionne toutes les informations de la table 'categories'
                        $requete = "SELECT * FROM categories";

                        // Exécution de notre requête via la méthode "query()" qui retourne un jeu de résultats en tant qu'objet PDO Statement  
                        // et on met ce résultat dans une variable  $result
                        $result = $db->query($requete);

                        // Grace à la méthode "rowCount()" nous pouvons connaitre le nombre de lignes retournées par la requête
                        $nbLigne = $result->rowCount(); 
                        if($nbLigne > 1)
                        {
                            while ($row = $result->fetch(PDO::FETCH_OBJ))
                            { ?>
                                <option  value="<?php echo $row->cat_id ?>"> <?php echo $row->cat_nom ?> </option> 
                                <br>

                        <?php
                            }
                    
                            // sert à finir proprement une série de fetch(), libère la connection au serveur de BDD
                            $result->closeCursor();
                        }
                        ?>    
                </select>
            <br><br>
            
            <!-- Le bouton ENREGISTRER -->
            <div style="margin:20px 0 20px  150px">
                <!-- Pour voir le code de la fonction "verif" regardez tout en bas de la page -->
                <input type="submit"  value="Enregistrer"  onclick="verif()"  style="float:left; margin-left:50px; padding:10px 15px; border-radius:10px; background-color:green; color:white"> 
            </div>
    
        </form>


        <!-- Les boutons RETOUR et DECONNEXION -->
        <div style="margin:20px 0 20px  200px">
            <a href="index.php"> 
                <button style="margin-left:50px; padding:10px 30px; border-radius:10px; background-color:grey; color:white"> Retour </button> 
            </a> 

            <a href="script_deconnexion.php"> 
                <button style="margin-left:50px; padding:10px 10px; border-radius:10px; background-color:blue; color:white"> Déconnexion </button> 
            </a> 
        </div>
    </div>


    <!-- PAGE FOOT -->
    <?php
        include("footer.php")
    ?>      



    <!-- JavaScript Code de la fonction verif() -->
    
    <script>

        //vérifie si on envoit ou non le formulaire à "script_modif.php"
        function verif()
        { 
            //Rappel : confirm() -> Bouton OK et Annuler, renvoie true ou false
            var resultat = confirm("Etes-vous certain de vouloir modifier cet enregistrement ?");

            //alert("retour :" + resultat);

            if (resultat==false)
            {
                alert("Vous avez annulé les modifications \nAucune modification ne sera apportée à cet enregistrement !");

                //annule l'évènement par défaut ... SUBMIT vers "script_modif.php"
                event.preventDefault();    
            }
        }
    </script>
          
</html>





