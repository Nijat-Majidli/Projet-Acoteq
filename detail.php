<?php 
    session_start();  
    /* ATTENTION
    Il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans lequel on manipulera cette 
    variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout echo ou quoi que ce soit d'autre : rien ne doit 
    avoir encore été écrit/envoyé à la page web.  */

    if (isset($_SESSION['email']))
    {
        echo 'Bonjour '. $_SESSION['email'] ;
    }
    else
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

        <title> Detail </title>

        <!-- Bootstrap CDN link -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
        <!-- Fichier CSS -->
        <link rel="stylesheet" href="css/style.css">
    </head>


    <!-- Code PHP -->
    <?php
        // Récupération de paramétre "demande_id" passé en GET dans le fichier "fournisseur.php": 
        $demande_id=$_GET['demande_id'];

        // Connection à la base de données 
        require "connection_bdd.php";

        $requete = "SELECT * FROM demande WHERE demande_id=".$demande_id;

        $result = $db->query($requete);

        // Si la requête renvoit un seul et unique résultat, on ne fait pas de boucle, ici c'est le cas: 
        $row = $result->fetch(PDO::FETCH_OBJ);

        //Libèration la connection au serveur de BDD
        $result->closeCursor();
    ?>


    <body>
        <div class="container">   
            <center> <h3> Détail de la demande </h3> </center> 
            
            <div class="form-group">
                <label for="titre"> Titre : </label> <br>
                <input id="titre" type="text" class="form-control" name="title" value=<?php echo $row->demande_titre?>  style="width:90%" readonly>
            </div>
            
            <div class="form-group">
                <label for="desc"> Description : </label> <br>
                <textarea id="desc" class="form-control" name="description" rows="10" style="resize:none" readonly> 
                    <?php echo $row->demande_description?> 
                </textarea>
            </div>

            <div class="form-group">
                <label for="somme"> Budget : </label> <br>
                <input id="somme" type="text" class="form-control" name="budget" value=<?php echo $row->demande_budget?>€  style="width:90%" readonly>
            </div>

            <div class="form-group">
                <label for="company"> Société : </label> <br>
                <input id="company" type="text" class="form-control" name="societe" value=<?php echo $row->raison_sociale?>  style="width:90%" readonly>
            </div>

            <div class="form-group">
                <label for="num_siren"> Numéro Siren : </label> <br>
                <input id="num_siren" type="text" class="form-control" name="siren" value=<?php echo $row->siren?>  style="width:90%" readonly>
            </div>

            <div class="form-group">
                <label for="rl"> Responsable légale : </label> <br>
                <input id="rl" type="text" class="form-control" name="responsable" value=<?php echo $row->responsable_legale?>  style="width:90%" readonly>
            </div>

            <div class="form-group">
                <label for="date"> Date création : </label> <br>
                <input id="date" type="text" class="form-control" name="creation" value=<?php echo $row->demande_creation?>  style="width:90%"  readonly>
            </div>

            <div class="form-group">
                <label for="time"> Date publication : </label> <br>
                <input id="time" type="text" class="form-control" name="publication" value=<?php echo $row->demande_publication?>  style="width:90%"  readonly>
            </div>

            <div style="text-align:center; margin-top:40px;"> 
                <a href="fournisseur.php"> <input type="button" class="btn btn-primary" value="Retour"> </a>
            </div>
            <br>

            <!-- Ajouter commentaire -->
            <a href="#" id="comment"> Ajouter commentaire </a>

            <form action="script_reponse.php" method="POST" style="display:none">
                <input name="commentaire" style="width:90%; height:10%; resize:none"> </input>
                <br><br>
                <center>
                    <button class="btn btn-success mr-3" type="submit"> Valider </button>
                    <input class="btn btn-warning mr-3" type="reset" value="Effacer"> 
                    <input class="btn btn-danger" type="button" id="cancel" value="Annuler"> 
                </center>
            </form>
        </div>


        <!-- Bootstrap Jquery, Popper -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    
        
        <!-- JQUERY Code -->
        <script>
            $(function(){
                $('#comment').click(function(){
                    $('form').show()});

                $('#cancel').click(function(){
                    $('form').hide()})
            })
        </script>
    </body>
</html>
    


