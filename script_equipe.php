<?php
    // Pour utiliser la variable superglobale "$_SESSION" il faut ajouter le fonction session_start() tout au début de la page:
    session_start();  

    /* On va enregistrer la date de création d'une nouvelle équipe. 
    Pour obtenir la bonne date et l'heure, il faut configurer la valeur de l'option <datetime_zone> sur la valeur Europe/Paris. 
    Donc, il faut ajouter l'instruction <date_default_timezone_set("Europe/Paris");> dans nos scripts avant toute manipulation de dates et heures.  */
    date_default_timezone_set('Europe/Paris');

    /* Nous récupérons les informations passées dans le fichier "equipe.php" dans la balise <form> et l'attribut action="script_equipe.php".  
    Les informations sont récupéré avec variable superglobale $_POST  */

    if(isset($_POST['equipe_nom']) && isset($_POST['equipe_membres']))
    {
        if (!empty($_POST['equipe_nom'] && $_POST['equipe_membres']))
        {
            // La fonction "trim()" efface les espaces blancs au début et à la fin d'une chaîne.
            // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS  
            $equipe_nom = trim(htmlspecialchars($_POST['equipe_nom']));         
            $equipe_membres = trim(htmlspecialchars($_POST['equipe_membres']));

            // Connexion à la base de données:         
            require ("connection_bdd.php");

            /* Avant d'insérer en base de données on convertit tout les caractères en minuscules de nos variables.
            La fonction strtolower() passe tout les caractères en minuscules :  */
            $equipe_nom = strtolower($equipe_nom);
            $equipe_membres = strtolower($equipe_membres);

            /* Construction de la requête préparée INSERT pour la table users. Les requêtes préparées empêchent les injections SQL.
            On n'insére pas la valeur pour la colonne "equipe_modification" car dans base de données on a bien défini que cette colonne 
            accepte la valeur NULL.   */ 
            $requete = $db->prepare("INSERT INTO equipe (equipe_nom, equipe_proprietaire, equipe_membres, equipe_creation, user_id, user_email) 
            VALUES (:equipe_nom, (SELECT CONCAT(client_nom, ' ', client_prenom) AS equipe_proprietaire FROM client WHERE user_email=:email), 
            :equipe_membres, :equipe_creation, (SELECT user_id FROM client WHERE user_email=:email), (SELECT user_email FROM client WHERE user_email=:email))");

            // Association des valeurs aux marqueurs via méthode "bindValue()"
            $requete->bindValue(':equipe_nom', $equipe_nom, PDO::PARAM_STR);
            $requete->bindValue(':equipe_membres', $equipe_membres, PDO::PARAM_STR);    
            $requete->bindValue(':email', $_SESSION['email'], PDO::PARAM_STR);

            // On utilise l'objet DateTime() pour enregistrer la date et l'heure de création de la nouvelle équipe dans la base de données
            $time = new DateTime();   
            $date = $time->format("Y/m/d H:i:s"); 
            $requete->bindValue(':equipe_creation', $date, PDO::PARAM_STR);

            // Exécution de la requête
            $requete->execute();

            // Libèration la connection au serveur de BDD
            $requete->closeCursor();

            // Aprés création d'une nouvelle équipe on envoie un email de notification à tous les membres d'équipe via la méthode mail() :
            mail($equipe_membres, "Nouvelle équipe", "Bonjour, une nouvelle équipe a été crée dont vous faites partie!", array('MIME-Version' => '1.0', 'Content-Type' => 'text/html; charset=utf-8', "From"=>"contact@acoteq.com", "X-Mailer" => "PHP/".phpversion()));
            
            echo '<h4> Votre équipe a été crée avec succès! </h4> ';
            header("refresh:2; url=equipe.php");   // refresh:2 signifie qu'après 2 secondes l'utilisateur sera redirigé vers la page equipe.php
            exit;

        }
        else
        {
            echo "<h4> Veuillez remplir tous les champs ! </h4>";
            header("refresh:2; url=equipe.php");  
            exit;
        }
    }
    else
    {
        echo "<h4> Veuillez remplir tous les champs ! </h4>";
        header("refresh:2; url=equipe.php");  
        exit;
    }    


    




?>
    
    
