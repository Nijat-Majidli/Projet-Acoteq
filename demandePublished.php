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
?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- Responsive design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title> Demandes publiées </title>

        <!-- Bootstrap CDN link -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
        <!-- Fichier CSS -->
        <link rel="stylesheet" href="css/style.css">
    </head>


    <body>
        <div class="container">
            <br>
            <center> <h3> Mes demandes publiées </h3> </center> 
            <br> <br>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col"> Titre </th>
                            <th scope="col"> Description </th>
                            <th scope="col"> Budget </th>
                            <th scope="col"> Date publication </th>
                            <th scope="col"> Commentaires </th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <!-- Code PHP -->
<?php
                        // Connéxion à la base de données 
                        require "connection_bdd.php";
                        
                        // On construit la requête SELECT : 
                        $requete = $db->prepare ("SELECT * FROM demande WHERE user_email=:email AND demande_etat=:etat");

                        // Association des valeurs aux marqueurs via la méthode "bindValue()" :
                        $requete->bindValue(':email', $_SESSION['email'], PDO::PARAM_STR);
                        $requete->bindValue(':etat', "publié", PDO::PARAM_STR);

                        // On exécute la requête :
                        $requete->execute();

                        // Grace à la méthode "rowCount()" on peut compter le nombre de lignes retournées par la requête :
                        $nbLigne = $requete->rowCount(); 
                        
                        if($nbLigne >= 1)
                        {
                            while ($row = $requete->fetch(PDO::FETCH_OBJ))  // Grace à méthode fetch() on choisit le 1er ligne de chaque colonne et la mets dans l'objet $row
                            {                                              // Avec la boucle "while" on choisit 2eme, 3eme, etc... lignes de chaque colonne et les mets dans l'objet $row
?>
                                <tr>
                                    <td> <?php echo $row->demande_titre;?> </td>
                                    <td> <?php echo $row->demande_description;?> </td>
                                    <td> <?php echo $row->demande_budget;?> </td>
                                    <td> <?php echo $row->demande_publication;?> </td>
                                    <td> 
                                        <a href="comments.php?demande_id=<?php echo $row->demande_id ?>"> Afficher </a>  
                                    </td>
                                </tr>                        
<?php           
                            }
                        }

                        // Libèration la connection au serveur de BDD
                        $requete->closeCursor();
?>    
                    </tbody>
                </table>

               
                <div style="text-align:center; margin-top:200px">
                    <a href="script_deconnexion.php"> <button class="btn btn-warning mr-3"> Déconnexion </button> </a> 
                    <a href="client.php"> <button class="btn btn-primary"> Retour </button> </a> 
                </div>
            </div>
        </div>

           
        <!-- Bootstrap Jquery, Popper -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    
    </body>
</html>