<?php 
    session_start();  
    /* ATTENTION
    Il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans lequel on manipulera cette 
    variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout echo ou quoi que ce soit d'autre : rien ne doit 
    avoir encore été écrit/envoyé à la page web.   */

    if (!isset($_SESSION['email']) && !isset($_SESSION['user_siren']) && !isset($_SESSION['role']))
    {
        echo "<h4> Cette page nécessite une identification </h4>";
        header("refresh:2; url=connexion.html");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page connexion.html
        exit;
    }

    /* Nous récupérons les informations passées dans le fichier "demandeDetail.php" dans la balise <a> et l'attribut "href"  
    Les informations sont récupéré avec variable superglobale $_GET   */
    if(isset($_GET['reponse_id']) && !empty($_GET['reponse_id']))
    {
        // La fonction "trim()" efface les espaces blancs au début et à la fin d'une chaîne.
        // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS
        $reponse_id = trim(htmlspecialchars((int)$_GET['reponse_id']));  // Pour vérifier que $_GET['reponse_id'] contient bien un nombre entier, on utilise (int) pour convertir la variable GET en type entier. 
    }
    else
    {
        echo "<h4> Veuillez indiquer le numéro de reponse ! </h4>";
        header("refresh:2; url=demandeDetail.php"); 
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

        <title> Reponse détail </title>

        <!-- Bootstrap CDN link -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
        <!-- Fichier CSS -->
        <link rel="stylesheet" href="css/style.css">

        <!-- JQuery Google CDN: -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body>
        <div class="container p-4 mt-3 col-7 bg-light text-dark">
            <form action="#"  method="#">   
<?php 
                // Connéxion à la base de données 
                require "connection_bdd.php";
                                    
                // On construit la requête SELECT : 
                $result = $db->prepare("SELECT * FROM reponse WHERE reponse_id=:reponse_id");
                
                // Association des valeurs aux marqueurs via la méthode "bindValue()" :
                $result->bindValue(':reponse_id', $reponse_id);

                // On exécute la requête :
                $result->execute();

                // Si la requête renvoit un seul et unique résultat, on ne fait pas de boucle, ici c'est le cas: 
                $row = $result->fetch(PDO::FETCH_OBJ);

                // Libèration la connection au serveur de BDD:
                $result->closeCursor();
?>
                <center> <h4> <?php echo $row->reponse_titre;?> </h4> </center>  
                <br>
                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Description </label>
                    <textarea class="form-control" rows="10" style="resize:none" readonly>
                        <?php echo $row->reponse_description;?>
                    </textarea>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Budget prévu </label>
                    <input type="number" class="form-control" value="<?php echo $row->reponse_budget;?>" readonly>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Date publication </label>
                    <!-- Ici on a besoin d'afficher une date qui provient de la base de données et qui est dans un format MySql: 2018-11-16
                    Pour formater cette date, on va utiliser l'objet de la classe DateTime et la méthode format:      -->
                    <?php $datePublication = new DateTime($row->reponse_publication);?>
                    <input type="text" class="form-control" name="budget" value="<?php echo $datePublication->format("d/m/Y H:\hi");?>" readonly>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Société </label>
                    <input type="text" class="form-control" value="<?php echo $row->reponse_societe;?>" readonly>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Publiée par </label>
                    <input type="text" class="form-control" value="<?php echo $row->reponse_proprietaire;?>" readonly>
                </div>
            </form>


            <!-- Les boutons Commenter, Déconnexion et Retour  -->
            <div style="text-align:center; margin-top:45px" id="buttons">
                <button class="btn btn-success mr-3" id="commenter"> Commenter </button> 
                <a href="script_deconnexion.php"> <button class="btn btn-warning mr-3"> Déconnexion </button> </a> 
<?php
                    if($_SESSION['role']=='client')
                    {
                        $retour = 'demandePublished.php';
                    }
                    else if($_SESSION['role']=='fournisseur')
                    {
                        $retour = 'fournisseur.php';
                    }
?>
                    <a href="<?php echo $retour;?>"> <button class="btn btn-primary"> Retour </button> </a> 
            </div>
            <br>

            <h5> Commentaires publiés : </h5>
            <hr>
            <?php
            // On construit la requête SELECT : 
            if($_SESSION['role']=="client")
            {
                $result = $db->prepare("SELECT * FROM commentaire WHERE reponse_id=:reponse_id");
                // Association des valeurs aux marqueurs via la méthode "bindValue()" :
                $result->bindValue(':reponse_id', $reponse_id);
            }
            else if($_SESSION['role']=="fournisseur")
            {
                $result = $db->prepare("SELECT * FROM commentaire WHERE reponse_id=:reponse_id AND comment_visibilite=:comment_visibilite");
                // Association des valeurs aux marqueurs via la méthode "bindValue()" :
                $result->bindValue(':reponse_id', $reponse_id);
                $result->bindValue(':comment_visibilite', 'visible');
            }   

            // On exécute la requête :
            $result->execute();

            // Grace à la méthode "rowCount()" on peut connaitre le nombre de lignes retournées par la requête
            $nbLigne = $result->rowCount(); 
                    
            if ($nbLigne >= 1)
            {
                while ($ligne = $result->fetch(PDO::FETCH_OBJ))   // Grace à la méthode fetch() on choisit 1er ligne de chaque colonne et on les mets dans l'objet $ligne                                            
                {
                    if(!empty($ligne))
                    {
                        /* Ici on a besoin d'afficher une date qui provient de la base de données et qui est dans un format MySql: 2018-11-16
                        Pour formater cette date, on va utiliser l'objet de la classe DateTime() et la méthode format():        */
                        $date = new DateTime($ligne->comment_publication);
                        
                        echo "Le ".$date->format("d/m/Y H:\hi")." ".$ligne->comment_proprietaire." de la société ".$ligne->comment_societe." a écrit: <br> <h6>".$ligne->comment_description."</h6>";
                        echo "<br>";
                    }
                    else
                    {
                        echo "<h5> Il n'y a aucuns commentaires pour cette réponse! </h5>";   
                    }
                }
            }
                   
            // Libèration la connection au serveur de BDD:
            $result->closeCursor();
?>


            <!-- Commentaire à écrire -->
            <form action="script_comment.php" method="POST" style="display:none; margin-top:2%" class="comments">
                <h4> Votre commentaire : </h4>
                <input type="hidden" name="reponse_id"  value="<?php echo $row->reponse_id;?>">
                <input type="hidden" name="fournisseur_email"  value="<?php echo $row->user_email;?>">
                <input type="hidden" name="client_email"  value="<?php echo $ligne->user_email;?>">

                <textarea class="form-control" name="comment" rows="10" cols="70" style="resize:none" required> </textarea>
<?php                
                if($_SESSION['role']=="client")
                {
?>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="visibilite" value="visible">
                        <label class="custom-control-label" for="customCheck1"> Visible par le fournisseur </label>
                    </div>
<?php
                }
?>
                <br>
                <center>
                    <div>
                        <button class="btn btn-success mr-3" type="submit"> Valider </button>
                        <input class="btn btn-warning mr-3" type="reset" value="Effacer"> 
                        <input class="btn btn-danger" type="button" id="cancel" value="Annuler"> 
                    </div>
                </center>
            </form>
            <br><br>
        </div>
        

        <!-- Bootstrap Jquery, Popper -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    

        <!-- JQuery code -->
        <script>
            $(document).ready(function()
            {
                $('#commenter').click(function()
                {
                    $('.comments').show(),
                    $('#buttons').hide()
                });

                $('#cancel').click(function(){
                    $('.comments').hide(),
                    $('#buttons').show()
                });
            })
        </script>
    </body>
</html>
