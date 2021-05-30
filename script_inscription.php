<?php

    /* On va enregistrer la date et l'heure d'inscription et dernier connexion de nouvel utilisateur. 
    Pour obtenir la bonne date et l'heure, il faut configurer la valeur de l'option <datetime_zone> sur la valeur Europe/Paris. 
    Donc, il faut ajouter l'instruction <date_default_timezone_set("Europe/Paris");> dans nos scripts avant toute manipulation de dates et heures.  */
    date_default_timezone_set('Europe/Paris');

    /* Nous récupérons les informations passées dans le fichier "inscription.html" dans la balise <form> et l'attribut action="script_inscription.php".  
    Les informations sont récupéré avec variable superglobale $_POST  */

    if(isset($_POST['userNom']) && isset($_POST['userPrenom']) && isset($_POST['RS']) && isset($_POST['numSiren']) && isset($_POST['adr']) && isset($_POST['codePostal']) && isset($_POST['ville']) && isset($_POST['pays']) && isset($_POST['mail']) && isset($_POST['mdp']) && isset($_POST['mdp2']))
    {
        if (!empty($_POST['userNom'] && $_POST['userPrenom'] && $_POST['RS'] && $_POST['numSiren'] && $_POST['adr'] && $_POST['codePostal'] && $_POST['ville'] && $_POST['pays'] && $_POST['mail'] && $_POST['mdp'] && $_POST['mdp2']))
        {
            // La fonction "trim()" efface les espaces blancs au début et à la fin d'une chaîne.
            // La fonction "htmlspecialchars()" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS  
            $user_nom = trim(htmlspecialchars($_POST['userNom']));
            $user_prenom = trim(htmlspecialchars($_POST['userPrenom']));
            $user_RS = trim(htmlspecialchars($_POST['RS']));         
            $user_siren = trim(htmlspecialchars($_POST['numSiren']));
            $user_role = trim(htmlspecialchars($_POST['userRole']));
            $user_adresse = trim(htmlspecialchars($_POST['adr']));
            $user_codePostal = trim(htmlspecialchars($_POST['codePostal']));
            $user_ville = trim(htmlspecialchars($_POST['ville']));
            $user_pays = trim(htmlspecialchars($_POST['pays']));
            $user_email = trim(htmlspecialchars($_POST['mail']));
            $user_mdp = trim(htmlspecialchars($_POST['mdp']));
            $user_mdp2 = trim(htmlspecialchars($_POST['mdp2']));
        }
        else
        {
            echo "<h4> Veuillez remplir tous les champs ! </h4>";
            header("refresh:2; url=inscription.html");  // refresh:2 signifie qu'après 2 secondes utilisateur sera redirigé sur la page inscription.html 
            exit;
        }
    }
    else
    {
        echo "<h4> Veuillez remplir tous les champs ! </h4>";
        header("refresh:2; url=inscription.html");  
        exit;
    }       


    /* Vérification avec l'expréssion RegExp la validité de format de tout les données saisi par utilisateur en utilisant 
    la fonction preg_match() qui renvoie True or False:        */
    if (!preg_match("#^[A-Za-z ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ-]+$#", $user_nom))  // aprés a-z on a ajouté un espace pour autoriser la saisi de l'espace blanc entre les mots
    {
        echo "<h4> Entrez un nom valide ! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }  
    else if (!preg_match("#^[A-Za-z ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ-]+$#", $user_prenom))
    {
        echo "<h4> Entrez un prénom valide ! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }  
    else if (!preg_match("#^[A-Za-z0-9 ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ_&!§£@*',.$;-]+$#", $user_RS))  
    {
        echo "<h4> Entrez un nom correct de la Raison Sociale ! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }  
    else if (!preg_match("#^[0-9]{9}$#", $user_siren))
    {
        echo "<h4> Entrez un numéro Siren valide ! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }  
    else if (!preg_match("#^[A-Za-z0-9 ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ_&!§£@*',.$;-]+$#", $user_adresse))
    {
        echo "<h4> Entrez une adresse valide ! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }  
    else if (!preg_match("#^[0-9]{5}$#", $user_codePostal))
    {
        echo "<h4> Entrez un code postal correct ! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }  
    else if (!preg_match("#^[A-Za-z0-9 ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ-]+$#", $user_ville))
    {
        echo "<h4> Entrez une ville correcte ! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }
    else if (!preg_match("#^[A-Za-z ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ-]+$#", $user_pays))
    {
        echo "<h4> Entrez un pays correct ! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }
    else if (!preg_match("#^[a-z0-9._ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $user_email))
    {
        echo "<h4> L'adresse mail n'a pas le bon format! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }


    /* Un mot de passe ne doit jamais être stocké en clair : il doit être crypté à l'aide d'un algorithme de cryptage afin que sa valeur 
    ne puisse être lue. La technique du grain de sel (appelée aussi salage, ou encore salt en anglais) consiste à ajouter une chaîne alphanumérique 
    au mot de passe lui-même. Le but est d'empêcher de retrouver le mot de passe d'origine à partir de sa chaîne hashée (appelée hash).
    La fonction password_hash() permet d’utiliser des algorithmes de cryptage en PHP et donc de générer le hash d’une chaîne de caractères, 
    grain de sel inclus.   
    D'abord on vérifie la validité du mot de passe:   */
    if ($user_mdp === $user_mdp2)
    {
        // Si le mot de passe est valide, on fait cryptage avec fonction password_hash()
        $user_mdp = password_hash($user_mdp, PASSWORD_DEFAULT);  // Ici 2eme paramètre PASSWORD_DEFAULT est l’algorithme de cryptage à utiliser, obligatoire.
    }
    else
    {
        echo "<h4> Le mot de passe n'est pas identique. </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }


    /* Vérification si l'adresse mail saisi par nouvel utilisateur déjà existe dans base de données ou non ?   
    Car on ne peut pas avoir 2 utilisateurs avec la même l'adresse mail. L'adresse mail doit être unique pour chaque utilisateur.
    Pour faire la vérification d'abord on va se connecter à la base de données:     */
    require ("connection_bdd.php");

    // Ensuite on construit la requête SELECT pour aller chercher la colonne user_email qui se trouvent dans la table "users" :     
    $requete = "SELECT user_email FROM users" ;
    
    // Grace à méthode query() on exécute notre requête et on ramene la colonne user_email et on la mets dans l'objet $result :     
    $result = $db->query($requete)  or  die(print_r($db->errorInfo()));  // Pour repérer l'erreur SQL en PHP on utilise le code die(print_r($db->errorInfo())) 

    // Grace à la méthode "rowCount()" nous pouvons connaitre le nombre de lignes retournées par la requête
    $nbLigne = $result->rowCount(); 
    
    if ($nbLigne >= 1)
    {
        while ($row = $result->fetch(PDO::FETCH_OBJ))   
        {   
            /* Grace à la méthode fetch() on choisit 1er ligne de la colonne user_email et on la mets dans l'objet $row. 
            Puis avec la boucle "while" on choisit 2eme, 3eme, etc.. lignes de la colonne user_email et on la mets dans l'objet $row   */                               
            if ($row->user_email == $user_email)
            {
                echo "<h4> Cette adresse mail déjà existe. Choisissez une autre! </h4>";
                header("refresh:2; url=inscription.html");
                exit;
            }   
        }
    }        
    

    /* Avant d'insérer en base de données on convertit tout les caractères en minuscules pour certaines variables.
    La fonction mb_strtolower() passe tout les caractères en minuscules :  */
    $user_nom = mb_strtolower($user_nom);
    $user_prenom = mb_strtolower($user_prenom);
    $user_RS = mb_strtolower($user_RS);         
    $user_adresse = mb_strtolower($user_adresse);
    $user_ville = mb_strtolower($user_ville);
    $user_pays = mb_strtolower($user_pays);


    /* Construction de la requête préparée INSERT pour la table users. Les requêtes préparées empêchent les injections SQL.
    On n'insére pas les valeurs pour les colonnes "login_fail", "user_blocked" et "unblock_time" car dans base de données on a bien 
    défini que ces colonnes acceptent la valeur NULL   */
    $requete = $db->prepare("INSERT INTO users (user_nom, user_prenom, user_raison_sociale, user_siren, user_role, 
    user_adresse, user_code_postal, user_ville, user_pays, user_email, user_mdp, user_inscription, user_connexion) 
    VALUES (:user_nom, :user_prenom, :user_raison_sociale, :user_siren, :user_role, :user_adresse, :user_code_postal, 
    :user_ville, :user_pays, :user_email, :user_mdp, :user_inscription, :user_connexion)");

    // Association des valeurs aux marqueurs via méthode "bindValue()"
    $requete->bindValue(':user_nom', $user_nom, PDO::PARAM_STR);
    $requete->bindValue(':user_prenom', $user_prenom, PDO::PARAM_STR);
    $requete->bindValue(':user_raison_sociale', $user_RS, PDO::PARAM_STR);
    $requete->bindValue(':user_siren', $user_siren, PDO::PARAM_INT);
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
    
    /* Ensuite selon le type de l'utilisateur(client ou fournisseur) on réalise la reqûete INSERT soit pour la table Client, 
    soit pour la table Fournisseur qui sont les enfants de la table parent Users.  
    En fait, le Primary Key de la table User est le Foreign Key pour les tables Client et Fournisseur.     */ 
    if($user_role == "client")
    {
        $requete = $db->prepare("INSERT INTO client (user_id, user_email, client_nom, client_prenom, client_raison_sociale, client_siren) 
        VALUES ((SELECT user_id FROM users WHERE user_email=:user_email), :user_email, :client_nom, :client_prenom, :client_raison_sociale, :client_siren)");
        $requete->bindValue(':user_email', $user_email, PDO::PARAM_STR);
        $requete->bindValue(':client_nom', $user_nom, PDO::PARAM_STR);
        $requete->bindValue(':client_prenom', $user_prenom, PDO::PARAM_STR);
        $requete->bindValue(':client_raison_sociale', $user_RS, PDO::PARAM_STR);
        $requete->bindValue(':client_siren', $user_siren, PDO::PARAM_INT);
        $requete->execute();
    }
    else if($user_role == "fournisseur")
    {
        $requete = $db->prepare("INSERT INTO fournisseur (user_id, user_email, fournisseur_nom, fournisseur_prenom, fournisseur_raison_sociale, fournisseur_siren) 
        VALUES ((SELECT user_id FROM users WHERE user_email=:user_email), :user_email, :fournisseur_nom, :fournisseur_prenom, :fournisseur_raison_sociale, :fournisseur_siren)");
        $requete->bindValue(':user_email', $user_email, PDO::PARAM_STR);
        $requete->bindValue(':fournisseur_nom', $user_nom, PDO::PARAM_STR);
        $requete->bindValue(':fournisseur_prenom', $user_prenom, PDO::PARAM_STR);
        $requete->bindValue(':fournisseur_raison_sociale', $user_RS, PDO::PARAM_STR);
        $requete->bindValue(':fournisseur_siren', $user_siren, PDO::PARAM_INT);
        $requete->execute();
    }

    // Libèration la connection au serveur de BDD
    $requete->closeCursor();

    // Redirection vers la page acceuil.php 
    header("Location: connexion.html");
    exit;   

    
?>

