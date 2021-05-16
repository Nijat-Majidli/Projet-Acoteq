<?php
    // Pour utiliser la variable superglobale "$_SESSION" il faut ajouter le fonction session_start() tout au début de la page:
    session_start();  

    /* On va enregistrer la date de création et publication de la nouvelle demande. Pour obtenir la bonne date et l'heure, 
    il faut configurer la valeur de l'option <<datetime_zone>> sur la valeur Europe/Paris. Donc, il faut ajouter 
    l'instruction <<date_default_timezone_set("Europe/Paris");>> dans nos scripts avant toute manipulation de dates.  */
    date_default_timezone_set('Europe/Paris');

    /* Nous récupérons les informations passées dans le fichier "detail.php" dans la balise <form> et l'attribut action="script_reponse.php".  
    Les informations sont récupéré avec variable superglobale $_POST  */
    if(isset($_POST['user_id']) && isset($_POST['user_email']) && isset($_POST['demande_id']) && isset($_POST['reponse_titre']) && isset($_POST['reponse_description']))
    {
        if (!empty($_POST['user_id'] && $_POST['user_email'] && $_POST['demande_id'] && $_POST['reponse_titre'] && $_POST['reponse_description']))
        {
            // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS  
            $user_id = htmlspecialchars($_POST['user_id']);
            $user_email = htmlspecialchars($_POST['user_email']);
            $demande_id = htmlspecialchars($_POST['demande_id']);
            $reponse_titre = htmlspecialchars($_POST['reponse_titre']);
            $reponse_description = htmlspecialchars($_POST['reponse_description']);
            $reponse_budget = htmlspecialchars($_POST['reponse_budget']);
        
            // Connection à la base de données 
            require "connection_bdd.php";

            // Construction de la requête INSERT avec la méthode prepare() sans injection SQL
            $requete = $db->prepare("INSERT INTO reponse (reponse_titre, reponse_description, reponse_budget, reponse_publication, 
            reponse_notification, fournisseur_raison_sociale, client_id, client_email, demande_id, fournisseur_id, fournisseur_email) 
            VALUES(:reponse_titre, :reponse_description, :reponse_budget, :reponse_publication, :reponse_notification, 
            (SELECT fournisseur_raison_sociale FROM fournisseur WHERE user_email=:email), :client_id, :client_email, :demande_id, 
            (SELECT user_id FROM fournisseur WHERE user_email=:email), (SELECT user_email FROM fournisseur WHERE user_email=:email))");
            

            // Association des valeurs aux marqueurs via la méthode "bindValue()"
            $requete->bindValue(':reponse_titre', $reponse_titre, PDO::PARAM_STR);
            $requete->bindValue(':reponse_description', $reponse_description, PDO::PARAM_STR);
            $requete->bindValue(':reponse_budget', $reponse_budget, PDO::PARAM_INT);
            
            // On utilise l'objet DateTime() pour enregistrer la date et l'heure de publication de reponse dans la base de données
            $time = new DateTime();   
            $date = $time->format("Y/m/d H:i:s"); 
            $requete->bindValue(':reponse_publication', $date, PDO::PARAM_STR);

            $requete->bindValue(':reponse_notification', 'envoyé', PDO::PARAM_STR);
            $requete->bindValue(':client_id', $user_id, PDO::PARAM_INT);
            $requete->bindValue(':client_email', $user_email, PDO::PARAM_STR);
            $requete->bindValue(':demande_id', $demande_id, PDO::PARAM_STR);
            $requete->bindValue(':email', $_SESSION['email'], PDO::PARAM_STR);

            // Exécution de la requête
            $requete->execute();

            //Libèration la connection au serveur de BDD
            $requete->closeCursor();

            // Si le fournisseur répond à la demande d'un client avec la méthode mail() on envoie un email de notification à ce client. 
            mail($user_email, "Nouvelle reponse", "Bonjour, Une nouvelle reponse a été publié!", array('MIME-Version' => '1.0', 'Content-Type' => 'text/html; charset=utf-8', "From"=>"contact@acoteq.com", "X-Mailer" => "PHP/".phpversion()));
        
            echo '<h4> Votre commentaire a été publié avec succès! </h4> ';
            header("refresh:2; url=fournisseur.php");   
            exit;
        }
        else
        {
            echo "<h4> Veuillez remplir tous les champs ! </h4>";
            header("refresh:2; url=detail.php");  // refresh:2 signifie qu'après 2 secondes l'utilisateur sera redirigé vers la page detail.php
            exit;
        }
    
    }
    else
    {
        echo "<h4> Veuillez remplir tous les champs ! </h4>";
        header("refresh:2; url=detail.php");  
        exit;
    }

?>