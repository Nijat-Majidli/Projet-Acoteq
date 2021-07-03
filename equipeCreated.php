<?php 
    session_start();  
    /* ATTENTION
    Il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans lequel on manipulera cette 
    variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout echo ou quoi que ce soit d'autre : rien ne doit 
    avoir encore été écrit/envoyé à la page web.  */

    if (!isset($_SESSION['email']) && !isset($_SESSION['user_siren']) && !isset($_SESSION['role'])=="client")
    {
        echo "<h4> Cette page nécessite une identification </h4>";
        header("refresh:2; url=connexion.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page connexion.php
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

        <title> Équipes crées </title>

        <!-- Bootstrap CDN link -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
        <!-- Fichier CSS -->
        <link rel="stylesheet" href="css/style.css">

        <!-- JQuery Google CDN: -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body>      
<?php
        // PAGE HEAD
        if (file_exists("header_client.php"))
        {
            include("header_client.php");
        }
        else
        {
            echo "le fichier n'existe pas";
        }
?>

        <!-- PAGE CONTENT -->
        <div class="container-fluid col-12">
            <br><br>
            <center> <h3> Mes équipes crées </h3> </center> 
            <br><br><br>
<?php
            // Connéxion à la base de données 
            require "connection_bdd.php";
            
            // On construit la requête SELECT : 
            $requete = $db->prepare ("SELECT * FROM equipe WHERE user_email=:user_email");

            // Association valeur de $_SESSION['email'] au marqueur :email via méthode "bindValue()"
            $requete->bindValue(':user_email', $_SESSION['email'], PDO::PARAM_STR);

            //On exécute la requête
            $requete->execute();

            // Grace à la méthode "rowCount()" on peut compter le nombre de lignes retournées par la requête
            $nbLigne = $requete->rowCount(); 
            
            if($nbLigne >= 1)
            {
?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col"> Nom </th>
                                <th scope="col"> Propriétaire </th>
                                <th scope="col"> Membres </th>
                                <th scope="col"> Email </th>
                                <th scope="col"> Crée </th>
                                <th scope="col"> Modifiée </th>
                                <th scope="col"> <center> Action </center> </th> 
                            </tr>
                        </thead>
<?php
                    while ($row = $requete->fetch(PDO::FETCH_OBJ))  // Grace à méthode fetch() on choisit le 1er ligne de chaque colonne et la mets dans l'objet $row
                    {                                              // Avec la boucle "while" on choisit 2eme, 3eme, etc... lignes de chaque colonne et les mets dans l'objet $row
?>              
                        <tbody>
                            <tr>
                                <td>  <?php echo $row->equipe_nom;?>  </td>
                                <td>  <?php echo $row->equipe_proprietaire;?>  </td>
                                <td>  <?php echo $row->equipe_membres;?>  </td> 
                                <td>  <?php echo $row->member_mails;?>  </td> 
                                <td>  <?php echo $row->equipe_creation;?>  </td>
                                <td>  <?php echo $row->equipe_modification;?>  </td>
                                <td> 
                                    <center>
                                        <a href="equipeModifier.php?equipe_id=<?php echo $row->equipe_id;?>"> 
                                            <button class="btn btn-secondary mr-1" type="button" onclick="Modifier()"> Modifier </button> 
                                        </a> 
                                    
                                        <a href="script_equipeSupprimer.php?equipe_id=<?php echo $row->equipe_id;?>"> 
                                            <button class="btn btn-danger" type="button" onclick="Supprimer()"> Supprimer </button> 
                                        </a>  
                                    </center>
                                </td>
                            </tr>
                        </tbody>
<?php
                    }
?>
                    </table>
                </div>
<?php
            }
            else
            {
                echo "<br> <center> <h5 style='color:red'> Pour l'instant vous avez aucune équipe crée ! </h5> </center> <br>";
                echo '<center> 
                        Pour créer une équipe veuillez cliquer : <a href="equipeNew.php"> Nouvelle équipe </a>
                    <center>';
            }

            // Libèration la connection au serveur de BDD
            $requete->closeCursor();
?> 

            <div style="text-align:center; margin-top:70px">
                <a href="script_deconnexion.php"> <button class="btn btn-warning mr-3"> Déconnexion </button> </a> 
                <a href="client.php"> <button class="btn btn-primary"> Retour </button> </a> 
            </div>
        </div>  


        <!-- Javascript codes -->
        <script>  

            function Modifier()
            { 
                // Rappel : window.confirm() -> Bouton "OK" renvoie true, mais bouton "Annuler" renvoie false
                var resultat = window.confirm("Etes-vous certain de vouloir modifier cette équipe ?");

                if (resultat==false)
                {
                    window.alert("Vous avez annulé les modifications \n Aucune modification ne sera apportée à cette équipe !");

                    // "event.preventDefault()" annule l'évènement par défaut (envoie vers la page "equipeModifier.php")
                    event.preventDefault();    
                }
            }


            function Supprimer()
            { 
                // Rappel : window.confirm() -> Bouton "OK" renvoie true, mais bouton "Annuler" renvoie false
                var resultat = window.confirm("Etes-vous certain de vouloir supprimer cette équipe ?");

                if (resultat==false)
                {
                    window.alert("Vous avez annulé la suppression de cette équipe !");

                    // "event.preventDefault()" annule l'évènement par défaut (envoie vers le fichier "script_equipeSupprimer.php")
                    event.preventDefault();    
                }
            }

        </script>
     


        <!-- Bootstrap Jquery, Popper -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    </body>
</html>
    