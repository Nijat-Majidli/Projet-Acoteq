<?php

    /* On va enregistrer la date de création d'une nouvelle équipe. 
    Pour obtenir la bonne date et l'heure, il faut configurer la valeur de l'option <datetime_zone> sur la valeur Europe/Paris. 
    Donc, il faut ajouter l'instruction <date_default_timezone_set("Europe/Paris");> dans nos scripts avant toute manipulation de dates et heures.  */
    date_default_timezone_set('Europe/Paris');

    /* Nous récupérons les informations passées dans le fichier "equipe.php" dans la balise <form> et l'attribut action="script_equipe.php".  
    Les informations sont récupéré avec variable superglobale $_POST  */

    if(isset($_POST['equipe']) && isset($_POST['membres']))
    {
        if (!empty($_POST['equipe'] && $_POST['membres']))
        {
            // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS  
            $equipe = htmlspecialchars($_POST['equipe']);         
            $membres = htmlspecialchars($_POST['membres']);
        }
        else
        {
            echo "<h4> Veuillez remplir tous les champs ! </h4>";
            header("refresh:2; url=equipe.php");  // refresh:2 signifie qu'après 2 secondes utilisateur sera redirigé sur la page inscription.html 
            exit;
        }
    }
    else
    {
        echo "<h4> Veuillez remplir tous les champs ! </h4>";
        header("refresh:2; url=equipe.php");  
        exit;
    }    



    /* Construction de la requête préparée INSERT pour la table users. Les requêtes préparées empêchent les injections SQL.
    On n'insére pas la valeur pour la colonne "equipe_modification" car dans base de données on a bien défini que cette colonne 
    accepte la valeur NULL   */
   
    $requete = $db->prepare("INSERT INTO equipe (equipe_nom, user_siren, user_responsable_legale, user_role, user_adresse, 
    user_code_postal, user_ville, user_pays, user_email, user_mdp, user_inscription, user_connexion) 
    VALUES (:user_raison_sociale, :user_siren, :user_responsable_legale, :user_role, :user_adresse, :user_code_postal, :user_ville, 
    :user_pays, :user_email, :user_mdp, :user_inscription, :user_connexion)");

    // Association des valeurs aux marqueurs via méthode "bindValue()"
    $requete->bindValue(':user_raison_sociale', $user_RS, PDO::PARAM_STR);
    $requete->bindValue(':user_siren', $user_siren, PDO::PARAM_INT);
    $requete->bindValue(':user_responsable_legale', $user_RL, PDO::PARAM_STR);
    $requete->bindValue(':user_role', $user_role, PDO::PARAM_STR);
    $requete->bindValue(':user_adresse', $user_adresse, PDO::PARAM_STR);
    $requete->bindValue(':user_code_postal', $user_codePostal, PDO::PARAM_INT);
    $requete->bindValue(':user_ville', $user_ville, PDO::PARAM_STR);
    $requete->bindValue(':user_pays', $user_pays, PDO::PARAM_STR);
    $requete->bindValue(':user_email', $user_email, PDO::PARAM_STR);
    $requete->bindValue(':user_mdp', $user_mdp, PDO::PARAM_STR);

    // On utilise l'objet DateTime() pour montrer la date d'inscription et l'heure du dernier connexion du user
    $time = new DateTime();   
    $date = $time->format("Y/m/d H:i:s"); 

    $requete->bindValue(':user_inscription', $date, PDO::PARAM_STR);  
    $requete->bindValue(':user_connexion', $date, PDO::PARAM_STR);

    // Exécution de la requête
    $requete->execute();






?>
    
    
