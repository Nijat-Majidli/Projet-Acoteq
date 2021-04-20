<?php

    /* On va enregistrer la date d'inscription et dernier connexion de nouveau client. Pour obtenir la bonne date et l'heure, 
    il faut configurer la valeur de l'option datetime_zone sur la valeur Europe/Paris.
    Donc, il faut ajouter l'instruction << date_default_timezone_set("Europe/Paris"); >> dans vos scripts avant toute manipulation de dates.   */
    date_default_timezone_set('Europe/Paris');


    /* Nous récupérons les informations passées dans le fichier "inscription.php" dans la balise <form> et l'attribut action="script_inscription.php".  
    Les informations sont récupéré avec variable superglobale $_POST  */

    if(isset($_POST['RS']) && isset($_POST['numSiren']) && isset($_POST['RL']) && isset($_POST['adr']) && isset($_POST['codePostal']) && isset($_POST['ville']) && isset($_POST['pays']) && isset($_POST['mail']) && isset($_POST['mdp']) && isset($_POST['mdp2']))
    {
        if (!empty($_POST['RS'] && $_POST['numSiren'] && $_POST['RL'] && $_POST['adr'] && $_POST['codePostal'] && $_POST['ville'] && $_POST['pays'] && $_POST['mail'] && $_POST['mdp'] && $_POST['mdp2']))
        {
            // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS  
            $user_RS = htmlspecialchars($_POST['RS']);         
            $user_numSiren = htmlspecialchars($_POST['numSiren']);
            $user_RL = htmlspecialchars($_POST['RL']);
            $user_adr = htmlspecialchars($_POST['adr']);
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
            header("refresh:2; url=inscription.html");  // refresh:2 signifie qu'après 2 secondes l'utilisateur sera redirigé sur la page inscription.php. 
            exit;
        }
    }

    
    /* Un mot de passe ne doit jamais être stocké en clair : il doit être crypté à l'aide d'un algorithme de cryptage afin que 
    sa valeur ne puisse être lue. La fonction <<password_hash()>> permet d’utiliser des algorithmes de cryptage en PHP.  
    D'abord on vérifie la validité du mot de passe:     */
    if ($user_mdp === $user_mdp2)
    {
        // Si le mot de passe est valide, on fait cryptage avec fonction password_hash()
        $user_mdp = password_hash($user_mdp, PASSWORD_DEFAULT);  
    }
    else
    {
        echo "<h4> Le mot de passe n'est pas identique. </h4>";
        header("refresh:2; url=inscription.html");
        exit;
    }


    /* Vérification si adresse mail saisi par utilisateur déjà existe dans la base de données ou non ?
    Pour cela d'abord on va se connecter à la base de données:     */
    require ("connection_bdd.php");

    // Ensuite on construit la requête SELECT pour aller chercher les colonnes client_email et fournisseur_email qui se trouvent dans les tables "client" et "fournisseur" :
    $req = "SELECT client_email, fournisseur_email FROM client, fournisseur" ;
    
    // Grace à méthode query() on exécute notre requête et on ramene les colonnes client_email et fournisseur_email et on les mets dans l'objet $result :
    $result = $db->query($req)  or  die(print_r($db->errorInfo()));  // Pour repérer l'erreur SQL en PHP on utilise le code die(print_r($db->errorInfo())) 

    // Grace à la méthode "rowCount()" nous pouvons connaitre le nombre de lignes retournées par la requête
    $nbLigne = $result->rowCount(); 
    
    if ($nbLigne >= 1)
    {
        while ($row = $result->fetch(PDO::FETCH_OBJ))   // Grace à la méthode fetch() on choisit 1er ligne des colonnes client_email et fournisseur_email et on les mets dans l'objet $row                                            
        {                                               // Avec la boucle "while" on choisit 2eme, 3eme, etc.. lignes des colonnes client_email et fournisseur_email et on les mets dans l'objet $row    

            if ($row->client_email == $user_email || $row->fournisseur_email == $user_email)
            {
                echo "<h4> Cette adresse mail déjà existe. Choisissez une autre! </h4>";
                header("refresh:2; url=inscription.html");
                exit;
            }   
        }
    }        
    

    // Construction de la requête préparée INSERT pour la table client
    // On insere pas la valeurs pour la colonne "login_fail" car dans base de données on a bien défini que cette colonne accepte la valeur 0
    if($_POST['userType']=='Client')
    {
        $requete = $db->prepare("INSERT INTO client (client_raison_sociale, client_siren, client_responsable_legale, client_adresse, 
        client_ville, client_pays, client_code_postal, client_email, client_password, client_date_inscription, client_connexion) 
        VALUES (:client_raison_sociale, :client_siren, :client_responsable_legale, :client_adresse, :client_ville, :client_pays, 
        :client_code_postal, :client_email, :client_password, :client_date_inscription, :client_connexion)");


    $user_RS 
    $user_numSiren
    $user_RL 
    $user_adr 
    $user_codePostal
    $user_ville 
    $user_pays 
    $user_email 



        // Vérification la validité de format de l'adresse mail avec REGEX en utilisant la fonction <<preg_match()>> qui renvoie True or False:
        if (!preg_match("#^[A-Za-z0-9 àâæçéèêëîïôœùûüÿ_&!§\$£@*',.-]+$#", $user_RS))
        {
            echo "<h4> Entrez un nom correct de la Raison Sociale! </h4>";
            header("refresh:2; url=inscription.html");
            exit;
        }  
        else if (!preg_match("#^[0-9]{9}$#", $user_numSiren))
        {
            echo "<h4> Entrez un numéro Siren valide! </h4>";
            header("refresh:2; url=inscription.html");
            exit;
        }  
        else if (!preg_match("#^[A-Za-z àâæçéèêëîïôœùûüÿ-]+$#", $user_RL))
        {
            echo "<h4> L'adresse mail n'a pas bon format! </h4>";
            header("refresh:2; url=inscription.html");
            exit;
        }  
        else if (!preg_match("#^[A-Za-z0-9 àâæçéèêëîïôœùûüÿ_&!§\$£@*',.-]+$#", $user_adr))
        {
            echo "<h4> L'adresse mail n'a pas bon format! </h4>";
            header("refresh:2; url=inscription.html");
            exit;
        }  
        else if (!preg_match("#^[0-9]{5}#", $user_codePostal))
        {
            echo "<h4> L'adresse mail n'a pas bon format! </h4>";
            header("refresh:2; url=inscription.html");
            exit;
        }  
        else if (!preg_match("#^[A-Za-z àâæçéèêëîïôœùûüÿ-]+$#", $user_ville))
        {
            echo "<h4> L'adresse mail n'a pas bon format! </h4>";
            header("refresh:2; url=inscription.html");
            exit;
        }
        else if (!preg_match("#^[A-Za-z àâæçéèêëîïôœùûüÿ-]+$#", $user_pays))
        {
            echo "<h4> L'adresse mail n'a pas bon format! </h4>";
            header("refresh:2; url=inscription.html");
            exit;
        }
        else if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $user_email))
        {
            echo "<h4> L'adresse mail n'a pas bon format! </h4>";
            header("refresh:2; url=inscription.html");
            exit;
        }
        else
        {
            // Association des valeurs aux marqueurs via méthode "bindValue()"
            $requete->bindValue(':client_raison_sociale', $user_RS, PDO::PARAM_STR);
            $requete->bindValue(':client_siren', $user_numSiren, PDO::PARAM_INT);
            $requete->bindValue(':client_responsable_legale', $user_RL, PDO::PARAM_STR);
            $requete->bindValue(':client_adresse', $user_adr, PDO::PARAM_STR);
            $requete->bindValue(':client_ville', $user_ville, PDO::PARAM_STR);
            $requete->bindValue(':client_pays', $user_pays, PDO::PARAM_STR);
            $requete->bindValue(':client_code_postal', $user_codePostal, PDO::PARAM_INT);
            $requete->bindValue(':client_email', $user_email, PDO::PARAM_STR);
            $requete->bindValue(':client_password', $user_mdp, PDO::PARAM_STR);
        
            // On utilise l'objet DateTime() pour montrer la date d'inscription et l'heure du dernier connexion du client
            $time = new DateTime();   
            $date = $time->format("Y/m/d H:i:s"); 

            $requete->bindValue(':client_date_inscription', $date, PDO::PARAM_STR);  
            $requete->bindValue(':client_connexion', $date, PDO::PARAM_STR);

            // Exécution de la requête
            $requete->execute();
            
            //Libèration la connection au serveur de BDD
            $requete->closeCursor();

            //Redirection vers la page acceuil.php 
            // header("Location: connexion.html");
            // exit;
        } 
    }

    else
    {
        // Construction de la requête préparée INSERT pour la table fournisseur
        $requete = $db->prepare("INSERT INTO fournisseur (fournisseur_raison_sociale, fournisseur_siren, fournisseur_responsable_legale, 
        fournisseur_adresse, fournisseur_ville, fournisseur_pays, fournisseur_code_postal, fournisseur_email, fournisseur_password, 
        fournisseur_date_inscription, fournisseur_connexion) VALUES (:fournisseur_raison_sociale, :fournisseur_siren, :fournisseur_responsable_legale, 
        :fournisseur_adresse, :fournisseur_ville, :fournisseur_pays, :fournisseur_code_postal, :fournisseur_email, :fournisseur_password, 
        :fournisseur_date_inscription, :fournisseur_connexion)");


        // Vérification la validité de format de l'adresse mail avec REGEX en utilisant la fonction <<preg_match()>> qui renvoie True or False:
        if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $user_email))  
        {
            // Association des valeurs aux marqueurs via méthode "bindValue()"
            $requete->bindValue(':fournisseur_raison_sociale', $user_RS, PDO::PARAM_STR);
            $requete->bindValue(':fournisseur_siren', $user_numSiren, PDO::PARAM_INT);
            $requete->bindValue(':fournisseur_responsable_legale', $user_RL, PDO::PARAM_STR);
            $requete->bindValue(':fournisseur_adresse', $user_adr, PDO::PARAM_STR);
            $requete->bindValue(':fournisseur_ville', $user_ville, PDO::PARAM_STR);
            $requete->bindValue(':fournisseur_pays', $user_pays, PDO::PARAM_STR);
            $requete->bindValue(':fournisseur_code_postal', $user_codePostal, PDO::PARAM_INT);
            $requete->bindValue(':fournisseur_email', $user_email, PDO::PARAM_STR);
            $requete->bindValue(':fournisseur_password', $user_mdp, PDO::PARAM_STR);
        
            // On utilise l'objet DateTime() pour montrer la date d'inscription et l'heure du dernier connexion du client
            $time = new DateTime();   
            $date = $time->format("Y/m/d H:i:s"); 

            $requete->bindValue(':fournisseur_date_inscription', $date, PDO::PARAM_STR);  
            $requete->bindValue(':fournisseur_connexion', $date, PDO::PARAM_STR);

            // Exécution de la requête
            $requete->execute();
            
            //Libèration la connection au serveur de BDD
            $requete->closeCursor();

            //Redirection vers la page acceuil.php 
            // header("Location: connexion.html");
            // exit;
        } 
        else
        {
            echo "<h4> L'adresse mail n'a pas bon format! </h4>";
            // header("refresh:2; url=inscription.html");
            // exit;
        }
    }


       
    


    
?>



