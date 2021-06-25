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

        <title> Nouvelle équipe </title>

        <!-- Bootstrap CDN link -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
        <!-- Fichier CSS -->
        <link rel="stylesheet" href="css/style.css">

        <!-- JQuery Google CDN: -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body>
        <!-- PAGE HEAD -->        
        <?php
            if (file_exists("header_client.php"))
            {
                include("header_client.php");
            }
            else
            {
                echo "file 'header_client.php' n'existe pas";
            }
        ?>

        <!-- PAGE CONTENT -->
        <div class="container">
            <br>
            <h3> Créer une équipe </h3>
            <hr> <br>

            <form action="script_equipeNew.php" method="POST" autocomplete="off">
                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label for="team"> Nom d'équipe : </label> 
                    <input id="team" type="text" class="form-control" name="equipe_nom" required>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label for="myInput"> Choisir les membres : </label> 
                    <input id="myInput" type="search" class="form-control">

                    <select id="liste" class="custom-select" style="display:none;" size="5">
                        <!-- Code Php -->
<?php 
                        // Connéxion à la base de données
                        require ("connection_bdd.php");

                        // On construit la requête SELECT via la méthode prepare() pour éviter injection SQL : 
                        $requete = $db->prepare("SELECT * FROM users WHERE user_siren = :user_siren");

                        /* Comme on veux choisir les utilisateurs qui travaillent dans la même société on utuilise user_siren, 
                        car les utilisateurs travaillant dans la même société possede le même numéro de siren.       */ 
                        $requete->bindValue(':user_siren', $_SESSION['user_siren'], PDO::PARAM_INT);

                        // On exécute la requête
                        $requete->execute();

                        // Grace à la méthode "rowCount()" nous pouvons connaitre le nombre de lignes retournées par la requête
                        $nbLigne = $requete->rowCount(); 

                        if ($nbLigne >= 1)
                        {
                            while ($row = $requete->fetch(PDO::FETCH_OBJ))   
                            {                       
?>
                                <option> 
                                    <?php echo $row->user_nom;?> <?php echo $row->user_prenom;?> (<?php echo $row->user_email;?>) 
                                </option>
<?php
                            }
                        } 
?>      
                    </select>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label for="member"> Membres d'équipe : </label> <br>
                    <input id="member" type="text" name="equipe_membres" style="width:100%; border:none; border-bottom:solid 1px #D5DBDB; outline:none"> 
                </div>

                 <!-- Les boutons <Valider> et <Annuler> -->
                 <div style="text-align: center; margin-top: 40px;">
                    <button type="submit" class="btn btn-success mr-3" id="bouton_valider"> Valider </button>
                    <a href="client.php"> <input type="button" class="btn btn-danger" value="Annuler"> </a>
                </div>
            </form>   
        </div>
       

        <!-- JQuery Code pour aller chercher et ajouter les membres d'équipe depuis base de données -->
        <script>
            $(document).ready(function() 
            {
                $('#myInput').keyup(function() 
                {
                    var inputValue = $(this).val().toLowerCase();
                    
                    $('select').css('display','block');

                    $('option').filter(function() 
                    {
                        $(this).toggle($(this).text().toLowerCase().indexOf(inputValue) > -1);
                    })
                });
                
                // On crée un tableau (array) vide: allMembers
                var allMembers=[];  

                $('select').change(function()
                {    
                    // Dans la variable "member" on récupére le contenu de la balise <option> cliquée (selected):
                    var member = $("option:selected").text();  
                   
                    // Puis avec la méthode push() on insére la variable "member" dans l'array allMembers:
                    allMembers.push($.trim(member));   // La méthode $.trim() est utilisée pour supprimer l'espace blanc du début et de la fin d'une chaîne

                    $("option:selected").click(function() 
                    {
                        $('#member').val((allMembers.join(", ")));  // La méthode join() convertit les éléments d'un tableau (array) sous forme d'une chaîne.
                                                                    // Le paramètre ", " insére l'éspace aprés les virgules entre les éléments. 
                    })
                });


                $('select').on(
                {
                    mouseleave: function() {
                    $(this).css('display','none')},
                
                    click: function() {
                    $(this).css('display','none')}
                });
            
            })

        </script>

            

        <!-- Bootstrap Jquery, Popper -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    </body>
</html>
    