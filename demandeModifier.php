<?php 
    session_start();  
    /* ATTENTION
    Il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans lequel on manipulera cette 
    variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout echo ou quoi que ce soit d'autre : rien ne doit 
    avoir encore été écrit/envoyé à la page web.  */

    if (!isset($_SESSION['email']) && !isset($_SESSION['role'])=="client")
    {
        echo "<h4> Cette page nécessite une identification </h4>";
        header("refresh:2; url=connexion.html");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page connexion.html
        exit;
    }
?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- Responsive design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title> Demande </title>

        <!-- Bootstrap CDN link -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
        <!-- Fichier CSS -->
        <link rel="stylesheet" href="css/style.css">
    </head>



    <body>
        <div class="container p-4 mb-3 mt-3 col-7 bg-light text-dark">
            <h2> Veuillez modifier votre demande </h2>
            <br>
            <!-- Code PHP -->
<?php
            // On récupérer le paramétre demande_id transmit par la page "demandeSaved.php" et on le met dans la variable $demande_id :
            $demande_id = $_GET['demande_id'];

            // Connéxion à la base de données 
            require "connection_bdd.php";
                
            // On construit la requête SELECT : 
            $requete = $db->prepare ("SELECT * FROM demande WHERE demande_id=:demande_id");

            // Association valeur de $_SESSION['email'] au marqueur :email via méthode "bindValue()"
            $requete->bindValue(':demande_id', $demande_id, PDO::PARAM_INT);

            //On exécute la requête
            $requete->execute();

            // Grace à la méthode "rowCount()" on peut compter le nombre de lignes retournées par la requête:
            $nbLigne = $requete->rowCount(); 

            if($nbLigne >= 1)
            {
                while ($row = $requete->fetch(PDO::FETCH_OBJ))  
                {                                           
?>
                    <!--Pour que le téléchargement soit possible, il faut ajouter l'attribut <<enctype>> à la balise <form>. La valeur doit être "multipart/form"-data-->
                    <form action="script_demandeModifier.php"  method="POST" enctype="multipart/form-data" autocomplete="off">   
                        <input type="hidden" name="demande_id" value="<?php echo $demande_id?>">  

                        <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                            <label for="title"> Titre <sup>*</sup> </label> 
                            <input type="text" class="form-control" id="title" name="titre" value="<?php echo $row->demande_titre?>"  required>
                        </div>

                        <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                            <label for="desc"> Description <sup>*</sup> </label>
                            <textarea id="desc" class="form-control" name="description" rows="10" style="resize:none" required> 
                                <?php echo $row->demande_description?> 
                            </textarea>
                        </div>

                        <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                            <label for="budgetDemande"> Budget prévu <sup>*</sup> </label>
                            <input type="number" class="form-control" id="budgetDemande" name="budget" value=<?php echo $row->demande_budget?> placeholder="en euro" required>
                        </div>

                        <div class="form-group mb-4">
                            <!--  Vous aurez sûrement besoin de limiter la taille du fichier. Il ne faudrait pas que quelqu'un s'amuse à uploader 
                            des fichiers de plusieurs Mo...  Pour limiter la taille du fichier uploadé, avant le champ de type file il faut 
                            ajouter un champ caché "hidden", lui donner le nom "MAX_FILE_SIZE" et lui donner en valeur, la taille maximum 
                            du fichier à uploader en octets(1Mo = 1000000 octets, 1Ko = 1000 octets). 
                            Ici on limite la taille du fichier à 5Mo :     -->
                            <input type="hidden" name="MAX_FILE_SIZE" value="5000000">

                            <label for="telecharger"> Télécharger votre demande <sup>*</sup> </label>
                            <input type="file" class="form-control-file" id="telecharger" name="fichier" required>
                        </div>

                        <p>  Vous voulez : </p>
                        <div class="form-check form-check-inline ml-3"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">  
                            <input class="form-check-input" type="radio" name="etat" id="save" value="sauvegardé" checked>
                            <label class="form-check-label" for="save"> Sauvegarder demande </label>
                        </div>
                        <div class="form-check form-check-inline"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                            <input class="form-check-input" type="radio" name="etat" id="publish" value="publié">
                            <label class="form-check-label" for="publish"> Publier demande </label>
                        </div>

                        <div style="text-align:center; margin-top:40px;">
                            <input type="submit" class="btn btn-success mr-3" value="Valider"> </input>  
                            <a href="demandeSaved.php"> <input type="button" class="btn btn-danger" value="Annuler"> </a>
                        </div>
                    </form>
        </div>
<?php
                }
            }
    
            //Libèration la connection au serveur de BDD
            $requete->closeCursor();
?>

  
  

        <!-- Bootstrap Jquery, Popper -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

        
        <!-- fichier Javascript RegExp -->
        <script src="javascript/RegExp2.js"> </script>
        
    </body>
</html>