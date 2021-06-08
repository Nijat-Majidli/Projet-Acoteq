<?php
    // Pour utiliser la variable superglobale "$_SESSION" il faut ajouter le fonction session_start() tout au début de la page:
    session_start();  

    /* On va enregistrer la date et l'heure de publication de la nouvelle commentaire. 
    Pour obtenir la bonne date et l'heure, il faut configurer la valeur de l'option <<datetime_zone>> sur la valeur Europe/Paris. 
    Donc, il faut ajouter l'instruction <<date_default_timezone_set("Europe/Paris");>> dans nos scripts avant toute manipulation de dates.  */
    date_default_timezone_set('Europe/Paris');

    /* Nous récupérons les informations passées dans le fichier "comments.php" dans la balise <form> et l'attribut action="script_comment.php".  
    Les informations sont récupéré avec variable superglobale $_POST  */
    if(isset($_POST['reponse_id']) && isset($_POST['client_email']) && isset($_POST['fournisseur_email']) && isset($_POST['comment']))
    {
        if (!empty($_POST['reponse_id'] && $_POST['client_email'] && $_POST['fournisseur_email'] && $_POST['comment']))
        {
            
            // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS  
            $reponse_id = htmlspecialchars($_POST['reponse_id']);  
            $client_email = htmlspecialchars($_POST['client_email']);
            $fournisseur_email = htmlspecialchars($_POST['fournisseur_email']); 
            $comment_description = trim(htmlspecialchars($_POST['comment']));  // La fonction "trim()" efface les espaces blancs au début et à la fin d'une chaîne.
            
            if(isset($_POST['visibilite'])=='visible')
            {
                $comment_visibilite = $_POST['visibilite'];
            }
            else
            {
                $comment_visibilite = 'non';
            }
             

            /*  Avant d'insérer en base de données on convertit tout les caractères en minuscules de variables. Comme la fonction strtolower() 
            ne convertit pas les lettres accentuées et les caractères spéciaux en minuscules, ici on utilise la fonction mb_strtolower() 
            qui passe tout les caractères majuscules (lettres normales, lettres accentuées, caractères spéciaux) en minuscules.   */  
            $comment_description = mb_strtolower($comment_description);

            // Connection à la base de données 
            require "connection_bdd.php";
     
            // Construction de la requête INSERT avec la méthode prepare() sans injection SQL
            if($_SESSION['role']=="client")
            {
                $requete = $db->prepare("INSERT INTO commentaire (comment_proprietaire, comment_societe, comment_description, 
                comment_publication, comment_visibilite, user_id, user_email, user_id_1, user_email_1, reponse_id) 
                VALUES((SELECT CONCAT(client_nom, ' ', client_prenom) AS comment_proprietaire FROM client WHERE user_email=:client_email), 
                (SELECT client_raison_sociale FROM client AS comment_societe WHERE user_email=:client_email), :comment_description, 
                :comment_publication, :comment_visibilite, (SELECT user_id FROM client WHERE user_email=:client_email), 
                (SELECT user_email FROM client WHERE user_email=:client_email), 
                (SELECT user_id FROM fournisseur AS user_id_1 WHERE user_email=:fournisseur_email), 
                (SELECT user_email FROM fournisseur AS user_email_1 WHERE user_email=:fournisseur_email), :reponse_id)");

                $requete->bindValue(':comment_visibilite', $comment_visibilite, PDO::PARAM_STR);
            }
            else if($_SESSION['role']=="fournisseur")
            {
                $requete = $db->prepare("INSERT INTO commentaire (comment_proprietaire, comment_societe, comment_description, 
                comment_publication, comment_visibilite, user_id, user_email, user_id_1, user_email_1, reponse_id) 
                VALUES((SELECT CONCAT(fournisseur_nom, ' ', fournisseur_prenom) AS comment_proprietaire FROM fournisseur WHERE user_email=:fournisseur_email), 
                (SELECT fournisseur_raison_sociale FROM fournisseur AS comment_societe WHERE user_email=:fournisseur_email), 
                :comment_description, :comment_publication, :comment_visibilite, 
                (SELECT user_id FROM client WHERE user_email=:client_email), (SELECT user_email FROM client WHERE user_email=:client_email), 
                (SELECT user_id FROM fournisseur AS user_id_1 WHERE user_email=:fournisseur_email), 
                (SELECT user_email FROM fournisseur AS user_email_1 WHERE user_email=:fournisseur_email), :reponse_id)");

                $requete->bindValue(':comment_visibilite', 'visible', PDO::PARAM_STR);
            }
            
            // Association des valeurs aux marqueurs via la méthode "bindValue()"
            $requete->bindValue(':comment_description', $comment_description, PDO::PARAM_STR);

            // On utilise l'objet DateTime() pour enregistrer la date et l'heure de publication de commentaire dans la base de données     */
            $time = new DateTime();   
            $date = $time->format("Y/m/d H:i:s"); 
            $requete->bindValue(':comment_publication', $date, PDO::PARAM_STR);
  
            $requete->bindValue(':client_email', $client_email, PDO::PARAM_STR);          
            $requete->bindValue(':fournisseur_email', $fournisseur_email, PDO::PARAM_STR);
            $requete->bindValue(':reponse_id', $reponse_id, PDO::PARAM_INT);

            // Exécution de la requête
            $requete->execute();

            //Libèration la connection au serveur de BDD
            $requete->closeCursor();

            if($_SESSION['role']=="client")
            {
                // Si le fournisseur répond au commentaire du client on envoie un email de notification à ce client avec la méthode mail() : 
                mail($client_email, "Nouvelle reponse", "Bonjour, le fournisseur a commenté!", array('MIME-Version' => '1.0', 'Content-Type' => 'text/html; charset=utf-8', "From"=>"contact@acoteq.com", "X-Mailer" => "PHP/".phpversion()));

                $page = "demandePublished.php";
            }
            else if($_SESSION['role']=="fournisseur")
            {
                // Si le client répond au commentaire du fournisseur on envoie un email de notification à ce fournisseur avec la méthode mail() : 
                mail($fournisseur_email, "Nouvelle reponse", "Bonjour, le client a commenté!", array('MIME-Version' => '1.0', 'Content-Type' => 'text/html; charset=utf-8', "From"=>"contact@acoteq.com", "X-Mailer" => "PHP/".phpversion()));

                $page = "fournisseur.php";   
            }

            echo '<h4> Votre commentaire a été publié avec succès! </h4> ';  
            header("refresh:2; url=$page");   
            exit;

        }
        else
        {
            echo "<h4> Veuillez remplir tous les champs ! </h4>";
            header("refresh:2; url=$page"); 
            exit;
        }
    }
    else
    {
        echo "<h4> Veuillez remplir tous les champs ! </h4>";
        header("refresh:2; url=$page");  
        exit;
    }



?>
