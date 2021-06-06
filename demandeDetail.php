<?php 
    session_start();  
    /* ATTENTION
    Il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans lequel on manipulera cette 
    variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout echo ou quoi que ce soit d'autre : rien ne doit 
    avoir encore été écrit/envoyé à la page web.   */

    if (!isset($_SESSION['email']) && !isset($_SESSION['user_siren']) && !isset($_SESSION['role'])=="client")
    {
        echo "<h4> Cette page nécessite une identification </h4>";
        header("refresh:2; url=connexion.html");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page connexion.html
        exit;
    }

    /* Nous récupérons les informations passées dans le fichier "demandePublished.php" dans la balise <a> et l'attribut "href"  
    Les informations sont récupéré avec variable superglobale $_GET   */
    if(isset($_GET['demande_id']) && !empty($_GET['demande_id']))
    {
        // La fonction "trim()" efface les espaces blancs au début et à la fin d'une chaîne.
        // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS
        $demande_id = trim(htmlspecialchars((int)$_GET['demande_id']));  // Pour vérifier que $_GET['demande_id'] contient bien un nombre entier, on utilise (int) pour convertir la variable GET en type entier. 
    }
    else
    {
        echo "<h4> Veuillez indiquer le numéro de demande ! </h4>";
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

        <title> Demande détail </title>

        <!-- Bootstrap CDN link -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
        <!-- Fichier CSS -->
        <link rel="stylesheet" href="css/style.css">

        <!-- JQuery Google CDN: -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body>
        <div class="container p-4 mb-3 mt-3 col-7 bg-light text-dark">
            <form action="#"  method="#">   
<?php 
                // Connéxion à la base de données 
                require "connection_bdd.php";
                                    
                // On construit la requête SELECT : 
                $result = $db->prepare("SELECT * FROM demande WHERE demande_id=:demande_id");
                
                // Association des valeurs aux marqueurs via la méthode "bindValue()" :
                $result->bindValue(':demande_id', $demande_id);

                // On exécute la requête :
                $result->execute();

                // Si la requête renvoit un seul et unique résultat, on ne fait pas de boucle, ici c'est le cas: 
                $row = $result->fetch(PDO::FETCH_OBJ);
?>
                <center> <h4> <?php echo $row->demande_titre;?> </h4> </center>  
                <br>
                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Description </label>
                    <textarea class="form-control" rows="10" style="resize:none" readonly>
                        <?php echo $row->demande_description;?>
                    </textarea>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Budget prévu </label>
                    <input type="number" class="form-control" value="<?php echo $row->demande_budget;?>" readonly>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Date création </label>
                    <input type="text" class="form-control" value="<?php echo $row->demande_creation;?>" readonly>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Date modification </label>
                    <input type="text" class="form-control" name="budget" value="<?php echo $row->demande_modification;?>" readonly>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Date publication </label>
                    <input type="text" class="form-control" name="budget" value="<?php echo $row->demande_publication;?>" readonly>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Équipe </label>
                    <input type="text" class="form-control" name="budget" value="<?php echo $row->demande_equipe;?>" readonly>
                </div>
            </form>

            <br><br>

            <h5> Réponses </h5>  
            <div class="table-responsive">
                <table class="table table-striped" style="margin-bottom:0;">
                    <thead>
                        <tr>
                            <th scope="col"> Titre </th>
                            <th scope="col"> Date publication </th>
                            <th scope="col"> Société </th>
                            <th scope="col"> Détail </th>
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

                        // Grace à la méthode "rowCount()" on peut connaitre le nombre de lignes retournées par la requête
                        $nbLigne = $result->rowCount(); 
                        
                        if ($nbLigne >= 1)
                        {
                            while ($row = $result->fetch(PDO::FETCH_OBJ))   // Grace à la méthode fetch() on choisit 1er ligne de chaque colonne et on les mets dans l'objet $row                                            
                            { 
                                if(!empty($row))
                                {  
?>                          
                                    <tr>
                                        <td> <?php echo $row->reponse_titre; ?> </td>
                                        <!-- Ici on a besoin d'afficher une date qui provient de la base de données et qui est dans un format MySql: 2018-11-16
                                        Pour formater cette date, on va utiliser l'objet de la classe DateTime() et la méthode format():         -->
                                        <?php $date = new DateTime($row->reponse_publication); ?>

                                        <td> <?php echo $date->format("d/m/Y H:\hi"); ?> </td>

                                        <td> <?php echo $row->reponse_societe; ?> </td>
                                        <td> 
                                            <a href="reponseDetail.php?reponse_id=<?php echo $row->reponse_id; ?>"> Afficher </a>  
                                        </td>
                                    </tr>
<?php
                                }
                                else
                                {
                                    echo "<h5> Il n'y a aucunes réponses pour cette demande! </h5>";   
                                }
                            }
                        }
                            
                        // Libèration la connection au serveur de BDD:
                        $result->closeCursor();
?>
                    </tbody>
                </table>
                <br>
                   
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
    
    </body>
</html>
