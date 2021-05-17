<?php
    // Pour utiliser la variable superglobale "$_SESSION" il faut ajouter le fonction session_start() tout au début de la page:
    session_start();  

    /* On va enregistrer la date de création et publication de la nouvelle demande. Pour obtenir la bonne date et l'heure, 
    il faut configurer la valeur de l'option <<datetime_zone>> sur la valeur Europe/Paris. Donc, il faut ajouter 
    l'instruction <<date_default_timezone_set("Europe/Paris");>> dans nos scripts avant toute manipulation de dates.  */
    date_default_timezone_set('Europe/Paris');

    /* Nous récupérons les informations passées dans le fichier "comments.php" dans la balise <form> et l'attribut action="script_comments.php".  
    Les informations sont récupéré avec variable superglobale $_POST  */
    if(isset($_POST['comment']) && isset($_POST['reponse_id']) && isset($_POST['user_email']))
    {
        if (!empty($_POST['comment'] && $_POST['reponse_id'] && $_POST['user_email']))
        {
            // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS  
            $comment_description = htmlspecialchars($_POST['comment']);
            $reponse_id = htmlspecialchars($_POST['reponse_id']);  
            $fournisseur_email = htmlspecialchars($_POST['user_email']); 
            
            // Connection à la base de données 
            require "connection_bdd.php";
     
            // Construction de la requête INSERT avec la méthode prepare() sans injection SQL
            $requete = $db->prepare("INSERT INTO commentaire (comment_publication, comment_description, client_raison_sociale, client_id, 
            client_email, reponse_id) VALUES(:comment_publication, :comment_description, 
            (SELECT client_raison_sociale FROM client WHERE user_email=:email), (SELECT user_id FROM client WHERE user_email=:email), 
            (SELECT user_email FROM client WHERE user_email=:email), :reponse_id)");
            
            /* Association des valeurs aux marqueurs via la méthode "bindValue()"
            On utilise l'objet DateTime() pour enregistrer la date et l'heure de publication de commentaire dans la base de données     */
            $time = new DateTime();   
            $date = $time->format("Y/m/d H:i:s"); 
            $requete->bindValue(':comment_publication', $date, PDO::PARAM_STR);
            
            $requete->bindValue(':comment_description', $comment_description, PDO::PARAM_STR);
            $requete->bindValue(':reponse_id', $reponse_id, PDO::PARAM_INT);
            $requete->bindValue(':email', $_SESSION['email'], PDO::PARAM_STR);

            // Exécution de la requête
            $requete->execute();

            //Libèration la connection au serveur de BDD
            $requete->closeCursor();

            // Si le fournisseur répond à la demande d'un client avec la méthode mail() on envoie un email de notification à ce client. 
            mail($fournisseur_email, "Nouvelle reponse", "Bonjour, le client a commenté à votre reponse!", array('MIME-Version' => '1.0', 'Content-Type' => 'text/html; charset=utf-8', "From"=>"contact@acoteq.com", "X-Mailer" => "PHP/".phpversion()));

            echo '<h4> Votre commentaire a été publié avec succès! </h4> ';      // refresh:2 signifie qu'après 2 secondes l'utilisateur sera redirigé vers la page comments.php
            header("refresh:2; url=comments.php");   
            exit;
        }
        else
        {
            echo "<h4> Veuillez remplir tous les champs ! </h4>";
            header("refresh:2; url=comments.php"); 
            exit;
        }
    }
    else
    {
        echo "<h4> Veuillez remplir tous les champs ! </h4>";
        header("refresh:2; url=comments.php");  
        exit;
    }



?>
