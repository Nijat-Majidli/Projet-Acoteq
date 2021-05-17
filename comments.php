<?php 
    session_start();  
    /* ATTENTION
    Il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans lequel on manipulera cette 
    variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout echo ou quoi que ce soit d'autre : rien ne doit 
    avoir encore été écrit/envoyé à la page web.   */

    if (!isset($_SESSION['email']) && !isset($_SESSION['role'])=="client")
    {
        echo "<h4> Cette page nécessite une identification </h4>";
        header("refresh:2; url=connexion.html");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page connexion.html
        exit;
    }

    /* Nous récupérons les informations passées dans le fichier "demandePublished.php" dans la balise <a> et l'attribut "href"  
    Les informations sont récupéré avec variable superglobale $_GET   */
    if(isset($_GET['demande_id'])) 
    {   
        if(!empty($_GET['demande_id']))
        {
            $demande_id = (int)$_GET['demande_id'];  // Pour vérifier que $_GET['demande_id'] contient bien un nombre entier, on utilise (int) pour convertir la variable GET en type entier. 
        }
        else
        {
            echo "<h4> Veuillez indiquer le bon numéro de demande ! </h4>";
            header("refresh:2; url=demandePublished.php"); 
            exit;
        }
    }
    else
    {
        echo " ";
        header("refresh:2; url=demandePublished.php"); 
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

        <title> Comments </title>

        <!-- Bootstrap CDN link -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
        <!-- Fichier CSS -->
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <div class="container">
            <br>
            <center> <h3> Commentaires </h3> </center> 
            <br> <br>
            <div class="table-responsive">
                <table class="table table-striped" style="margin-bottom:0;">
                    <thead>
                        <tr>
                            <th scope="col"> Titre </th>
                            <th scope="col"> Description </th>
                            <th scope="col"> Budget </th>
                            <th scope="col"> Date publication </th>
                            <th scope="col"> Société </th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Code PHP -->
<?php
                        // Connéxion à la base de données 
                        require "connection_bdd.php";
                        
                        // On construit la requête SELECT : 
                        $result = $db->prepare("SELECT * FROM reponse WHERE demande_id=:demande_id");
                        
                        // Association des valeurs aux marqueurs via la méthode "bindValue()" :
                        $result->bindValue(':demande_id', $demande_id);

                        // On exécute la requête :
                        $result->execute();

                        // Si la requête renvoit un seul et unique résultat, on ne fait pas de boucle, ici c'est le cas: 
                        $row = $result->fetch(PDO::FETCH_OBJ)              
?>                          
                        <tr>
                            <td> <?php echo $row->reponse_titre;?> </td>
                            <td> <?php echo $row->reponse_description;?> </td>
                            <td> <?php echo $row->reponse_budget;?> </td>
                            <td> <?php echo $row->reponse_publication;?> </td>
                            <td> <?php echo $row->fournisseur_raison_sociale;?> </td>
                            <td> <button class="btn btn-success" id="repondre"> Repondre </button> </td>
                        </tr>
<?php
                        // Libèration la connection au serveur de BDD:
                        $result->closeCursor();
?>
                    </tbody>
                </table>
                <br>
                
                <form action="script_comments.php" method="POST" style="display:none" class="comments">
                    <h4> Votre réponse : </h4>
                    <input type="hidden" name="reponse_id" value="<?php echo $row->reponse_id;?>">
                    <input type="hidden" name="user_email" value="<?php echo $row->user_email;?>">
                    <textarea class="form-control" name="comment" rows="10" cols="70" style="resize:none" required> </textarea>
                    <br>
                    <center>
                        <div class="comments" style="display:none;">
                            <button class="btn btn-success mr-3" type="submit"> Valider </button>
                            <input class="btn btn-warning mr-3" type="reset" value="Effacer"> 
                            <input class="btn btn-danger" type="button" id="cancel" value="Annuler"> 
                        </div>
                    </center>
                </form>
                   
                <div style="text-align:center; margin-top:100px" id="buttons">
                    <a href="script_deconnexion.php"> <button class="btn btn-warning mr-3"> Déconnexion </button> </a> 
                    <a href="demandePublished.php"> <button class="btn btn-primary"> Retour </button> </a> 
                </div>
                        
                    
            </div>
        </div>

        <!-- Bootstrap Jquery, Popper -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    

        <!-- JQuery code -->
        <script>
            $(document).ready(function(){
                $('#repondre').click(function(){
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
