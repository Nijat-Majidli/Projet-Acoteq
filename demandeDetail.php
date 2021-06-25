<?php 
    session_start();  
    /* ATTENTION
    Le fonction session_start() démarre le système de sessions. Il est impératif d'utiliser cette fonction au début de chaque 
    fichier PHP dans lequel on utilisera la variable superglobale $_SESSION et avant tout envoi de requêtes HTTP, c'est-à-dire 
    avant tout code HTML (donc avant la balise <!DOCTYPE> ).  */  

    if (!isset($_SESSION['email']) && !isset($_SESSION['user_siren']) && !isset($_SESSION['role']))
    {
        echo "<h4> Cette page nécessite une identification </h4>";
        header("refresh:2; url=connexion.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page connexion.php
        exit;
    }

    /* Nous récupérons les informations passées soit dans le fichier "client.php" ou "fournisseur.php" dans la balise <a> et l'attribut "href"  
    Les informations sont récupéré avec variable superglobale $_GET   */
    if(isset($_GET['demande_id']) && !empty($_GET['demande_id']))
    {
        // La fonction "trim()" efface les espaces blancs au début et à la fin d'une chaîne.
        // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS
        $demande_id = trim(htmlspecialchars((int)$_GET['demande_id']));  // Pour vérifier que $_GET['demande_id'] contient bien un nombre entier, on utilise (int) pour convertir la variable GET en type entier. 
    }
    else
    {
        if($_SESSION['role']=='client')
        {
            $page='client.php';
        }
        elseif($_SESSION['role']=='fournisseur')
        {
            $page='fournisseur.php';
        }

        echo "<h4> Veuillez indiquer le numéro de demande ! </h4>";
        header("refresh:2; url=$page"); 
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
        <!-- PAGE HEAD -->        
        <?php
            if($_SESSION['role']=='client')
            {
                if (file_exists("header_client.php"))
                {
                    include("header_client.php");
                }
                else
                {
                    echo "le fichier n'existe pas";
                }
            }
            elseif($_SESSION['role']=='fournisseur')
            {
                if (file_exists("header_fournisseur.php"))
                {
                    include("header_fournisseur.php");
                }
                else
                {
                    echo "le fichier n'existe pas";
                }
            }
        ?>


        <!-- PAGE CONTENT -->
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
                    <textarea class="form-control text-left" rows="10" style="resize:none; " readonly>
                        <?php echo $row->demande_description;?>
                    </textarea>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Budget prévu </label>
                    <input type="number" class="form-control" value="<?php echo $row->demande_budget;?>" readonly>
                </div>
<?php
            /* Si l'utilisateur est un client (pas fournisseur) on lui montre certain informations supplémantaires :
            1. Equipe de demande;
            2. Date création de la demande;
            3. Date modification de la demande.   */
            if($_SESSION['role']=="client")
            {
?>
                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Équipe </label>
                    <input type="text" class="form-control" name="budget" value="<?php echo $row->demande_equipe;?>" readonly>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Date création </label>
                    <!-- Ici on a besoin d'afficher une date qui provient de la base de données et qui est dans un format MySql: 2018-11-16
                    Pour formater cette date, on va utiliser l'objet de la classe DateTime et la méthode format:      -->
                    <?php $dateCreation = new DateTime($row->demande_creation);?>
                    <input type="text" class="form-control" value="<?php echo $dateCreation->format("d/m/Y H:\hi");?>" readonly>
                </div>

                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Date modification </label>
                    <?php $dateModification = new DateTime($row->demande_modification);?>
                    <input type="text" class="form-control" name="budget" value="<?php echo $dateModification->format("d/m/Y H:\hi");?>" readonly>
                </div>
<?php
            }
?>
                <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label> Date publication </label>
                    <?php $datePublication = new DateTime($row->demande_publication);?>
                    <input type="text" class="form-control" name="budget" value="<?php echo $datePublication->format("d/m/Y H:\hi");?>" readonly>
                </div>
            </form>
            <br>

<?php
            /* Les boutons Modifier, Publier et Supprimer  
            Seul la proprietaire de la demande (client qui a crée la demande) peut la modifier, publier ou supprimer :  */
            if($_SESSION['role']=="client" && $row->user_email==$_SESSION['email'])
            {
?>
                <div style="text-align:center; margin:10px 0 15px 0"  id="buttons">
                    <a href="demandeModifier.php?demande_id=<?php echo $row->demande_id;?>"> 
                        <button class="btn btn-primary mr-3" type="button" onclick="modifier()"> Modifier </button> 
                    </a> 
                    
                    <a href="script_demandePublier.php?demande_id=<?php echo $row->demande_id;?>"> 
                        <button class="btn btn-success mr-3" type="button" onclick="publier()"> Publier </button> 
                    </a>  
                    
                    <a href="script_demandeSupprimer.php?demande_id=<?php echo $row->demande_id ?>"> 
                        <input class="btn btn-danger" type="button" onclick="supprimer()" value="Supprimer"> 
                    </a> 
                </div> 
<?php
            }            
            else if($_SESSION['role']=="fournisseur")
            {
                /* Bouton Répondre 
                Seul le fournisseur peut écrire la reponse à la demande du client     */
                echo '<center> <input type="button" id="repondre" value="Répondre" class="btn btn-success mr-3"> </center>';
?>
                <form action="script_reponse.php" method="POST" style="display:none; margin-top:20px;" id="answer">
                    <input type="hidden" name="user_email" value="<?php echo $row->user_email?>">   
                    <input type="hidden" name="demande_id" value="<?php echo $demande_id?>"> 
                    
                    <h5> Votre réponse </h5>
                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="title"> Titre <sup>*</sup> </label> 
                        <input type="text" class="form-control" id="title" name="reponse_titre" style="width:90%" required>
                    </div>

                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="desc"> Description <sup>*</sup> </label>
                        <textarea id="desc" class="form-control text-left" name="reponse_description" rows="10" style="width:90%; resize:none" required> 
                            <?php echo $row->reponse_description?> 
                        </textarea>
                    </div>

                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="prix"> Votre tarif proposé : <sup>*</sup> </label> 
                        <input type="number" class="form-control" id="prix" name="reponse_budget" style="width:15%" required>
                    </div>
                    <center>
                        <button class="btn btn-success mr-3" type="submit"> Valider </button>
                        <input class="btn btn-warning mr-3" type="reset" value="Effacer"> 
                        <input class="btn btn-danger" type="button" id="cancel" value="Annuler"> 
                    </center>
                </form>
                <br><br>
<?php
            }
?>
            <br>
            <h5> Réponses : </h5> 
<?php
            // Connéxion à la base de données :
            require "connection_bdd.php";
            
            if($_SESSION['role']=="client")
            {
                // On construit la requête SELECT : 
                $result = $db->prepare("SELECT * FROM reponse WHERE demande_id=:demande_id");
                
                // Association des valeurs aux marqueurs via la méthode "bindValue()" :
                $result->bindValue(':demande_id', $demande_id);
            }
            else if($_SESSION['role']=="fournisseur")
            {
                // On construit la requête SELECT : 
                $result = $db->prepare("SELECT * FROM reponse WHERE user_email=:user_email AND demande_id=:demande_id");
            
                // Association des valeurs aux marqueurs via la méthode "bindValue()" :
                $result->bindValue(':user_email', $_SESSION['email']);
                $result->bindValue(':demande_id', $demande_id);
            }

            // On exécute la requête :
            $result->execute();

            // Grace à la méthode "rowCount()" on peut connaitre le nombre de lignes retournées par la requête
            $nbLigne = $result->rowCount(); 
            
            if ($nbLigne >= 1)
            {
                while ($row = $result->fetch(PDO::FETCH_OBJ))  // Grace à la méthode fetch() on choisit 1er ligne de chaque colonne et on les mets dans l'objet $row                                            
                { 
?>                          
                    <div class="table-responsive">
                        <table class="table table-striped" style="margin-bottom:2%;">
                            <thead>
                                <tr>
                                    <th scope="col"> Titre </th>
                                    <th scope="col"> Société </th>
                                    <th scope="col"> Publiée </th>
                                    <th scope="col"> Détail </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td> <?php echo $row->reponse_titre;?> </td>
                                    <td> <?php echo $row->reponse_societe;?> </td>

                                    <!-- Ici on a besoin d'afficher une date qui provient de la base de données et 
                                    qui est dans un format MySql: 2018-11-16.
                                    Pour formater cette date, on va utiliser l'objet de la classe DateTime et la méthode format :   -->
                                    <?php $date = new DateTime($row->reponse_publication);?>
                                    <td> <?php echo $date->format("d/m/Y H:\hi");?> </td>

                                    <!-- On envoie en URL (méthode GET) le paramètre reponse_id vers la page reponseDetail.php :  -->
                                    <td> <a href="reponseDetail.php?reponse_id=<?php echo $row->reponse_id;?>"> Afficher </a> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
<?php
                }
            }
            else
            {
                echo "<h6 style='color:red'> Il n'y a aucunes réponses pour cette demande! </h6>";   
            }

            // Libèration la connection au serveur de BDD:
            $result->closeCursor();
?>            
        </div>



        <!-- Javascript Codes -->
        <script>  

            function modifier()
            { 
                //Rappel : confirm() -> Bouton OK et Annuler, renvoie true ou false
                var resultat = confirm("Etes-vous certain de vouloir modifier cette demande ?");

                // alert("retour :" + resultat);

                if (resultat==false)
                {
                    alert("Vous avez annulé les modifications \n Aucune modification ne sera apportée à cette demande!");

                    //annule l'évènement par défaut ... SUBMIT vers "demandeModifier.php"
                    event.preventDefault();    
                }
            }


            function publier()
            {
                var resultat = window.confirm("Êtes-vous sûr de vouloir publier votre demande?")

                if (resultat==false)
                {
                    alert("Vous avez annulé publication!");

                    //annule l'évènement par défaut ... SUBMIT vers "script_demandePublier.php"
                    event.preventDefault();    
                }
            }

            
            function supprimer()
            {
                var resultat = window.confirm("Êtes-vous sûr de vouloir supprimer votre demande?")

                if (resultat==false)
                {
                    alert("Vous avez annulé suppression!");

                    //annule l'évènement par défaut ... SUBMIT vers "script_demandeSupprimer.php"
                    event.preventDefault();    
                }
            }
     


            // JQUERY Codes 
            $(document).ready(function()
            {
                $('#repondre').click(function(){
                    $('#answer').show(),
                    $('#repondre').hide()
                });


                $('#cancel').click(function(){
                    $('#answer').hide(),
                    $('#repondre').show()
                })
            })
        </script>
       


        <!-- Bootstrap Jquery, Popper -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    
    </body>
</html>
