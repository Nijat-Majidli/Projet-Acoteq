<?php 
    session_start();  
    /* ATTENTION
    Il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans lequel on manipulera cette 
    variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout echo ou quoi que ce soit d'autre : rien ne doit 
    avoir encore été écrit/envoyé à la page web.  */

    if (!isset($_SESSION['email']) && !isset($_SESSION['user_siren']) && !isset($_SESSION['role'])=="client")
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

        <title> Équipe à modifier </title>

        <!-- Bootstrap CDN link -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
        <!-- Fichier CSS -->
        <link rel="stylesheet" href="css/style.css">

        <!-- JQuery Google CDN: -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>


    <body>
        <div class="container p-4 mb-3 mt-3 col-7 bg-light text-dark">
            <h2> Veuillez modifier votre équipe </h2>
            <br>
            <form action="script_equipeModifier.php" method="POST" autocomplete="off">
                <!-- Code PHP -->
<?php
                // On récupérer le paramétre equipe_id transmit par la page "equipeSaved.php" et on le met dans la variable $equipe_id :
                $equipe_id = $_GET['equipe_id'];

                // Connéxion à la base de données 
                require "connection_bdd.php";
                    
                // On construit la requête SELECT : 
                $requete = $db->prepare ("SELECT * FROM equipe WHERE equipe_id=:equipe_id");

                // Association valeur de $_SESSION['email'] au marqueur :email via méthode "bindValue()"
                $requete->bindValue(':equipe_id', $equipe_id, PDO::PARAM_INT);

                // On exécute la requête
                $requete->execute();

                // Grace à la méthode "rowCount()" on peut compter le nombre de lignes retournées par la requête:
                $nbLigne = $requete->rowCount(); 

                if($nbLigne >= 1)
                {
                    while ($row = $requete->fetch(PDO::FETCH_OBJ))  
                    {                                           
?>     
                        <input type="hidden" name="equipe_id" value="<?php echo $equipe_id?>">
                        <input type="hidden" name="equipe_proprietaire" value="<?php echo $row->equipe_proprietaire?>">
                        
                        <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                            <label for="team"> Nom d'équipe : </label> 
                            <input id="team" type="text" class="form-control" name="equipe_nom" value="<?php echo $row->equipe_nom?>" required>
                        </div>

                        <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                            <label for="member"> Membres d'équipe : </label> <br>
                            <input id="member" type="text" name="equipe_membres" value="<?php echo $row->equipe_membres?>" style="width:100%; border:none; border-bottom:solid 1px #D5DBDB; outline:none" required> 
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
                                $requete = $db->prepare ("SELECT * FROM client WHERE client_siren = :client_siren");

                                $requete->execute(array(':client_siren' => $_SESSION['user_siren']));   // L'écriture raccourcie: ici la méthode bindValue sera appellée "automatiquement".

                                // Grace à la méthode "rowCount()" nous pouvons connaitre le nombre de lignes retournées par la requête
                                $nbLigne = $requete->rowCount(); 

                                if ($nbLigne >= 1)
                                {
                                    while ($row = $requete->fetch(PDO::FETCH_OBJ))   
                                    {                       
?>
                                        <option> 
                                            <?php echo $row->user_email;?> 
                                        </option>
<?php
                                    }
                                } 

                                // Libèration la connection au serveur de BDD
                                $requete->closeCursor();
?>
                            </select>
                        </div>
<?php
                    }
                } 

                // Libèration la connection au serveur de BDD
                $requete->closeCursor();
?>      
                            
                <!-- Les boutons <Valider> et <Annuler> -->
                <div style="text-align: center; margin-top: 40px;">
                    <button type="submit" class="btn btn-success" id="bouton_valider"> Valider </button>
                    <a href="equipeSaved.php"> <input type="button" class="btn btn-danger" value="Annuler"> </a>
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


                $('select').change(function()
                {    
                     // On crée un tableau (array) vide: allMembers
                     var allMembers=[];  

                    // Dans la variable "newMembers" avec la méthode text() on récupére le contenu de la balise <option> cliquée (selected):
                    var newMembers = $("option:selected").text();  // newMembers contient la liste des nouvels membres
                   
                    // Puis avec la méthode push() on ajoute la variable "newMembers" dans l'array allMembers:
                    allMembers.push($.trim(newMembers));   // La méthode trim() est utilisée pour supprimer l'espace blanc du début et de la fin d'une chaîne

                    // Dans la variable "members" avec la méthode val() on récupére le contenu de la balise <input> avec ID="member" :
                    var members = $('#member').val();  // currentMembers contient la liste des membres actuels

                    // Puis avec la méthode push() on ajoute la variable "members" dans l'array allMembers:
                    allMembers.push(members);

                    $("option:selected").click(function() 
                    {
                        // Avec la méthode val() on ajoute l'array allMembers dans input avec ID="member":
                        $('#member').val($.trim(allMembers.join(", ")));  // La méthode join() retourne le tableau (array) sous forme de chaîne.
                    });
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

        
        <!-- fichier Javascript RegExp -->
        <script src="javascript/RegExp2.js"> </script>
        
    </body>
</html>