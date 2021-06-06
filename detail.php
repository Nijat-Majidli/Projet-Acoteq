<?php 
    session_start();  
    /* ATTENTION
    Il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans lequel on manipulera cette 
    variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout echo ou quoi que ce soit d'autre : rien ne doit 
    avoir encore été écrit/envoyé à la page web.   */

    if (!isset($_SESSION['email']) && !isset($_SESSION['user_siren']) && !isset($_SESSION['role'])=="fournisseur")
    {
        echo "<h4> Cette page nécessite une identification </h4>";
        header("refresh:2; url=connexion.html");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page connexion.html
        exit;
    }

    /* Nous récupérons les informations passées dans le fichier "fournisseur.php" dans la balise <a> et l'attribut "href"  
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
        header("refresh:2; url=fournisseur.php"); 
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
                    <label> Date publication </label>
                    <input type="text" class="form-control" value="<?php echo $row->demande_publication;?>" readonly>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Société </label>
                    <input type="text" class="form-control" value="<?php echo $row->demande_societe;?>" readonly>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Publiée par </label>
                    <input type="text" class="form-control" value="<?php echo $row->demande_proprietaire;?>" readonly>
                </div>
            </form>

            <div style="text-align:center; margin-top:40px;"> 
                <a href="fournisseur.php"> <input type="button" class="btn btn-primary" value="Retour" id="retour"> </a>
            </div>
            <br>

            <!-- Ajouter commentaire -->
            <a href="#title" id="comment"> Ajouter une réponse </a>
            <br><br>
            <form action="script_reponse.php" method="POST" style="display:none">
                <input type="hidden" name="user_email" value="<?php echo $row->user_email?>"> 
                <input type="hidden" name="demande_id" value="<?php echo $demande_id?>"> 
                
                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label for="title"> Titre <sup>*</sup> </label> 
                    <input type="text" class="form-control" id="title" name="reponse_titre" style="width:90%" required>
                </div>
                <textarea class="form-control" name="reponse_description" rows="10" style="width:90%; resize:none" required> </textarea>
                <br>
                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label for="prix"> Votre tarif proposé : <sup>*</sup> </label> 
                    <input type="number" class="form-control" id="prix" name="reponse_budget" style="width:15%" required>
                </div>
                <br><br>
                <center>
                    <button class="btn btn-success mr-3" type="submit"> Valider </button>
                    <input class="btn btn-warning mr-3" type="reset" value="Effacer"> 
                    <input class="btn btn-danger" type="button" id="cancel" value="Annuler"> 
                </center>
            </form>
        </div>
        <br> <br>


        <!-- Bootstrap Jquery, Popper -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    
        
        <!-- JQUERY Code -->
        <script>
            $(function(){
                $('#comment').click(function(){
                    $('form').show(),
                    $('#retour').hide()
                });

                $('#cancel').click(function(){
                    $('form').hide(),
                    $('#retour').show()
                })
            })
        </script>
    </body>
</html>
    


