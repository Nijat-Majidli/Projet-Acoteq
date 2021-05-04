<?php 
    session_start();  
    /* ATTENTION
    Il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans lequel on manipulera cette 
    variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout echo ou quoi que ce soit d'autre : rien ne doit 
    avoir encore été écrit/envoyé à la page web.  */

    if (isset($_SESSION['role']) && $_SESSION['role']=='admin')
    {
        echo 'Bonjour ' . $_SESSION['role'] ." ". $_SESSION['login'] ;
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

        <title> Ajout </title>
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


    <!-- PAGE MAIN CONTENT -->
    <div class="container"  style="margin-top:60px">
        <form  action="script_ajout.php"  method="post"  enctype="multipart/form-data"  style="padding-left: 100px">
            <!-- Upload photo of product -->
            <h5> Télécharger la photo du produit : </h5> 
            <input type="file"  name="fichier"  required> 
            <br><br>

            <label for="id_produit"> Produit ID : </label> <br>
            <input type="text"  name="id"  id="id_produit" style="width:90%"  required>
            <br><br>

            <label for="reference"> Réference : </label> <br>
            <input type="text"  name="ref"  id="reference"  style="width:90%"  required>
            <br><br>

            <label for="categorie"> Catégorie : </label> <br>
            <select id="categorie"  name="cat"  style="width:90%"> 
                    <!-- Code PHP -->
                    <?php
                    //Connéxion à la base de données 
                    require "connection_bdd.php";
                    
                    //Sélectionne toutes les informations de la table 'categories'
                    $requete = "SELECT * FROM categories";

                    // Exécution de notre requête via la méthode "query()" qui retourne un jeu de résultats en tant qu'objet PDO Statement  
                    // et on met ce résultat dans une variable $result
                    $result = $db->query($requete);

                    // Grace à la méthode "rowCount()" on peut compter le nombre de lignes retournées par la requête
                    $nbLigne = $result->rowCount(); 
                    
                    if($nbLigne > 1)
                    {
                        while ($row = $result->fetch(PDO::FETCH_OBJ))       // Grace à méthode fetch() on choisit le 1er ligne de chaque colonne et la mets dans l'objet $row
                        {                                                   // Avec la boucle "while" on choisit 2eme, 3eme, etc... lignes de chaque colonne et les mets dans l'objet $row
                    ?>
                            <option  value="<?php echo $row->cat_id ?>" required>  <?php echo $row->cat_nom ?>  </option> 
                            <br>
                    <?php
                        }
                
                        // sert à finir proprement une série de fetch(), libère la connection au serveur de BDD
                        $result->closeCursor();
                    }
                    ?>    
            </select>
            <br><br>

            <label for="libelle"> Libellé : </label> <br>
            <input type="text"  name="lib"  id="libelle"  style="width:90%"  required>
            <br><br>

            <label for="description"> Description : </label> <br>
            <input type="text"  name="desc"  id="description"  style="width:90%"  required>
            <br><br>

            <label for="prix"> Prix : </label> <br>
            <input type="text"  name="price"  id="prix"  style="width:90%"  required>
            <br><br>

            <label for="stock_produit"> Stock : </label> <br>
            <input type="text"  name="stock"  id="stock_produit"  style="width:90%"  required>
            <br><br>

            <label for="couleur"> Couleur : </label> <br>
            <input type="text"  name="color"  id="couleur"  style="width:90%"  required>
            <br><br>

            <label for="extension"> Extension de la photo : </label> <br>
            <input type="text"  name="ext"  id="extension"  style="width:90%"  required>
            <br><br>

            <label for="bloque"> Produit bloqué ? : </label> <br>
            <input type="radio"  name="bloq"  id="bloque"  checked> Oui
            <input type="radio"  name="bloq"  id="bloque"> Non
            <br><br>

             <!-- Bouton AJOUTER  -->
            <!-- Pour voir le code de la fonction "verif" regardez tout en bas de la page -->
            <div style="margin:20px 0 20px  150px">
                <input type="submit"  value="Ajouter"  onclick="verif()"  style="float:left; margin-left:100px; padding:10px 30px; border-radius:10px; background-color:green; color:white"> 
            </div>
        </form>


        <!-- Les boutons ANNULER et DECONNEXION -->
        <a href="index.php"> 
            <button style="margin-left:50px; padding:10px 30px; border-radius:10px; background-color:red; color:white"> Annuler </button> 
        </a> 

        <a href="script_deconnexion.php"> 
                <button style="margin-left:50px; padding:10px 10px; border-radius:10px; background-color:blue; color:white"> Déconnexion </button> 
        </a> 

    </div>



    <!-- PAGE FOOT -->
    <?php
        include("footer.php")
    ?>      



    <!-- JavaScript Code de la fonction verif() -->
    
    <script>

        //vérifie si on envoit ou non le formulaire à "script_ajout.php"
        function verif()
        { 
            //Rappel : confirm() -> Bouton OK et Annuler, renvoie true ou false
            var resultat = confirm("Etes-vous certain de vouloir ajouter ce produit ?");

            //alert("retour :" + resultat);

            if (resultat==false)
            {
                alert("Vous avez annulé les ajouts!");

                //annule l'évènement par défaut ... SUBMIT vers "script_ajout.php"
                event.preventDefault();    
            }
        }
    </script>
          
</html>


