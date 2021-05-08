<?php

    /* On va enregistrer la date d'inscription et dernier connexion de nouvel utilisateur. Pour obtenir la bonne date et l'heure, 
    il faut configurer la valeur de l'option <<datetime_zone>> sur la valeur Europe/Paris. Donc, il faut ajouter l'instruction 
    <<date_default_timezone_set("Europe/Paris");>> dans nos scripts avant toute manipulation de dates et heures.  */
    date_default_timezone_set('Europe/Paris');

    /* Nous récupérons les informations passées dans le fichier "inscription.html" dans la balise <form> et l'attribut action="script_inscription.php".  
    Les informations sont récupéré avec variable superglobale $_POST  */

    if(isset($_POST['RS']) && isset($_POST['numSiren']) && isset($_POST['RL']) && isset($_POST['adr']) && isset($_POST['codePostal']) && isset($_POST['ville']) && isset($_POST['pays']) && isset($_POST['mail']) && isset($_POST['mdp']) && isset($_POST['mdp2']))
    {
        if (!empty($_POST['RS'] && $_POST['numSiren'] && $_POST['RL'] && $_POST['adr'] && $_POST['codePostal'] && $_POST['ville'] && $_POST['pays'] && $_POST['mail'] && $_POST['mdp'] && $_POST['mdp2']))
        {
            // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS  
            $user_RS = htmlspecialchars($_POST['RS']);         
            $user_siren = htmlspecialchars($_POST['numSiren']);
            $user_RL = htmlspecialchars($_POST['RL']);
            $user_role = htmlspecialchars($_POST['userRole']);
            $user_adresse = htmlspecialchars($_POST['adr']);
            $user_codePostal = htmlspecialchars($_POST['codePostal']);
            $user_ville = htmlspecialchars($_POST['ville']);
            $user_pays = htmlspecialchars($_POST['pays']);
            $user_email = htmlspecialchars($_POST['mail']);
            $user_mdp = htmlspecialchars($_POST['mdp']);
            $user_mdp2 = htmlspecialchars($_POST['mdp2']);
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
    if (!preg_match("#^[A-Za-z0-9 àâæçéèêëîïôœùûüÿ_&!§£@*',.$;-]+$#", $user_RS))
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
    else if (!preg_match("#^[A-Za-z àâæçéèêëîïôœùûüÿ-]+$#", $user_RL))
    {
        echo "<h4> Entrez un nom et prénom valide ! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }  
    else if (!preg_match("#^[A-Za-z0-9 àâæçéèêëîïôœùûüÿ_&!§£@*',.$;-]+$#", $user_adresse))
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
    else if (!preg_match("#^[A-Za-z àâæçéèêëîïôœùûüÿ-]+$#", $user_ville))
    {
        echo "<h4> Entrez une ville correcte ! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }
    else if (!preg_match("#^[A-Za-z àâæçéèêëîïôœùûüÿ-]+$#", $user_pays))
    {
        echo "<h4> Entrez un pays correct ! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }
    else if (!preg_match("#^[a-z0-9._àâæçéèêëîïôœùûüÿ-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $user_email))
    {
        echo "<h4> L'adresse mail n'a pas bon format! </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }


    /* Un mot de passe ne doit jamais être stocké en clair : il doit être crypté à l'aide d'un algorithme de cryptage afin que sa valeur 
    ne puisse être lue. 
    La technique du grain de sel (appelée aussi salage, ou encore salt en anglais) consiste à ajouter une chaîne alphanumérique au mot de 
    passe lui-même. Le but est d'empêcher de retrouver le mot de passe d'origine à partir de sa chaîne hashée (appelée hash).
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


    /* Vérification si la Raison Sociale, le numéro SIREN et l'adresse mail saisi par nouvel utilisateur déjà existe dans notre base de 
    données ou non ?   Car on ne peut pas avoir 2 utilisateurs avec la même Raison Sociale, numéro SIREN ou l'adresse mail.
    Pour faire la vérification d'abord on va se connecter à la base de données:     */
    require ("connection_bdd.php");

    /* Ensuite on construit la requête SELECT pour aller chercher les colonnes user_raison_sociale, user_siren et user_email 
    qui se trouvent dans la table "users" :     */
    $req = "SELECT user_raison_sociale, user_siren, user_email FROM users" ;
    
    /* Grace à méthode query() on exécute notre requête et on ramene les colonnes user_raison_sociale, user_siren et user_email et 
    on les mets dans l'objet $result :     */
    $result = $db->query($req)  or  die(print_r($db->errorInfo()));  // Pour repérer l'erreur SQL en PHP on utilise le code die(print_r($db->errorInfo())) 

    // Grace à la méthode "rowCount()" nous pouvons connaitre le nombre de lignes retournées par la requête
    $nbLigne = $result->rowCount(); 
    
    if ($nbLigne >= 1)
    {
        while ($row = $result->fetch(PDO::FETCH_OBJ))   
        {   
            /* Grace à la méthode fetch() on choisit 1er ligne des colonnes user_raison_sociale, user_siren et user_email et 
            on les mets dans l'objet $row. Puis avec la boucle "while" on choisit 2eme, 3eme, etc.. lignes des colonnes 
            user_raison_sociale, user_siren et user_email et on les mets dans l'objet $row   */                                         
            if ($row->user_raison_sociale == $user_RS)
            {
                echo "<h4> Cette raison sociale déjà existe. Veuillez choisir une autre! </h4>";
                header("refresh:2; url=inscription.html");
                exit;
            }   
            if ($row->user_siren == $user_siren)
            {
                echo "<h4> Ce numéro de SIREN déjà existe. Veuillez choisir un autre! </h4>";
                header("refresh:2; url=inscription.html");
                exit;
            }   
            if ($row->user_email == $user_email)
            {
                echo "<h4> Cette adresse mail déjà existe. Choisissez une autre! </h4>";
                header("refresh:2; url=inscription.html");
                exit;
            }   
        }
    }        
    

    /* Construction de la requête préparée INSERT pour la table users. Les requêtes préparées empêchent les injections SQL.
    On n'insére pas les valeurs pour les colonnes "login_fail", "user_blocked" et "unblock_time" car dans base de données on a bien 
    défini que ces colonnes acceptent la valeur NULL   */
   
    $requete = $db->prepare("INSERT INTO users (user_raison_sociale, user_siren, user_responsable_legale, user_role, user_adresse, 
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
    
    /* Ensuite selon le type de l'utilisateur(client ou fournisseur) on réalise la reqûete INSERT soit pour la table Client, 
    soit pour la table Fournisseur qui sont les enfants de la table parent Users.  
    En fait, le Primary Key de la table User est le Foreign Key pour les tables Client et Fournisseur.     */ 
    if($user_role == "client")
    {
        $requete = $db->prepare("INSERT INTO client (user_id, user_email, client_raison_sociale, client_siren, client_responsable_legale) 
        VALUES ((SELECT user_id FROM users WHERE user_email=:mail), :user_email, :client_raison_sociale, :client_siren, :client_responsable_legale)");
        $requete->bindValue(':mail', $user_email, PDO::PARAM_STR);
        $requete->bindValue(':user_email', $user_email, PDO::PARAM_STR);
        $requete->bindValue(':client_raison_sociale', $user_RS, PDO::PARAM_STR);
        $requete->bindValue(':client_siren', $user_siren, PDO::PARAM_INT);
        $requete->bindValue(':client_responsable_legale', $user_RL, PDO::PARAM_STR);
        $requete->execute();
    }
    else if($user_role == "fournisseur")
    {
        $requete = $db->prepare("INSERT INTO fournisseur (user_id, user_email, fournisseur_raison_sociale, fournisseur_siren, fournisseur_responsable_legale) 
        VALUES ((SELECT user_id FROM users WHERE user_email=:mail), :user_email, :fournisseur_raison_sociale, :fournisseur_siren, :fournisseur_responsable_legale)");
        $requete->bindValue(':mail', $user_email, PDO::PARAM_STR);
        $requete->bindValue(':user_email', $user_email, PDO::PARAM_STR);
        $requete->bindValue(':fournisseur_raison_sociale', $user_RS, PDO::PARAM_STR);
        $requete->bindValue(':fournisseur_siren', $user_siren, PDO::PARAM_INT);
        $requete->bindValue(':fournisseur_responsable_legale', $user_RL, PDO::PARAM_STR);
        $requete->execute();
    }

    // Libèration la connection au serveur de BDD
    $requete->closeCursor();

    // Redirection vers la page acceuil.php 
    header("Location: connexion.html");
    exit;   


    
?>



