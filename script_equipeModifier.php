<?php
    // Pour utiliser la variable superglobale "$_SESSION" il faut ajouter le fonction session_start() tout au début de la page:
    session_start();  

    /* On va enregistrer la date et l'heure de modification de l'équipe. Pour obtenir la bonne date et l'heure, 
    il faut configurer la valeur de l'option <<datetime_zone>> sur la valeur Europe/Paris. Donc, il faut ajouter 
    l'instruction <<date_default_timezone_set("Europe/Paris");>> dans nos scripts avant toute manipulation de dates.  */
    date_default_timezone_set('Europe/Paris');

    /* Nous récupérons les informations passées dans le fichier "equipeModifier.php" dans la balise <form>  et 
    l'attribut action="script_equipeModifier.php".   
    Les informations sont récupéré avec variable superglobale $_POST     */
    if(isset($_POST['equipe_id']) && isset($_POST['equipe_proprietaire']) && isset($_POST['equipe_nom']) && isset($_POST['equipe_membres']))
    {
        if (!empty($_POST['equipe_id'] && $_POST['equipe_proprietaire'] && $_POST['equipe_nom'] && $_POST['equipe_membres']))
        {
            // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS  
            $equipe_id = htmlspecialchars($_POST['equipe_id']);
            $equipe_proprietaire = htmlspecialchars($_POST['equipe_proprietaire']);
            $equipe_nom = htmlspecialchars($_POST['equipe_nom']);
            $equipe_membres = htmlspecialchars($_POST['equipe_membres']);

            // Connection à la base de données 
            require "connection_bdd.php";

            // Construction de la requête INSERT avec la méthode prepare() sans injection SQL
            $requete = $db->prepare("UPDATE equipe SET equipe_proprietaire=:equipe_proprietaire, equipe_nom=:equipe_nom, 
            equipe_membres=:equipe_membres, equipe_modification=:equipe_modification WHERE equipe_id=:equipe_id");

            // Association des valeurs aux marqueurs via méthode "bindValue()"
            $requete->bindValue(':equipe_proprietaire', $equipe_proprietaire, PDO::PARAM_STR);
            $requete->bindValue(':equipe_nom', $equipe_nom, PDO::PARAM_STR);
            $requete->bindValue(':equipe_membres', $equipe_membres, PDO::PARAM_STR); 
            $requete->bindValue(':equipe_id', $equipe_id, PDO::PARAM_INT);

            // On utilise l'objet DateTime() pour enregistrer la date et l'heure de creation et publication de demande dans la base de données
            $time = new DateTime();   
            $date = $time->format("Y/m/d H:i:s"); 

            $requete->bindValue(':equipe_modification', $date, PDO::PARAM_STR);

            // Exécution de la requête
            $requete->execute();

            //Libèration la connection au serveur de BDD
            $requete->closeCursor();

            // Aprés création d'une nouvelle équipe on envoie un email de notification à tous les membres d'équipe via la méthode mail() :
            mail($equipe_membres, "Modification d'équipe", "Bonjour, une modification d'équipe a été effectuée!", array('MIME-Version' => '1.0', 'Content-Type' => 'text/html; charset=utf-8', "From"=>"contact@acoteq.com", "X-Mailer" => "PHP/".phpversion()));
        
            echo '<h4> Votre équipe a été modifié avec succès! </h4> ';
            header("refresh:2; url=equipeSaved.php");   // refresh:2 signifie qu'après 2 secondes l'utilisateur sera redirigé vers la page equipeSaved.php
            exit;
        }
        else
        {
            echo "<h4> Veuillez remplir tous les champs ! </h4>";
            header("refresh:2; url=equipeModifier.php");  
            exit;
        }
    }
    else
    {
        echo "<h4> Veuillez remplir tous les champs ! </h4>";
        header("refresh:2; url=equipeModifier.php");  
        exit;
    }       


            

?>