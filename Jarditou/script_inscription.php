<?php

    /* On va enregistrer la date d'inscription et dernier connexion de nouveau client. Pour obtenir la bonne date et heure, il faut 
    configurer la valeur de l'option datetime_zone sur la valeur Europe/Paris.
    Donc, il faut ajouter l'instruction date_default_timezone_set("Europe/Paris"); dans vos scripts avant toute manipulation de dates.   */
    date_default_timezone_set('Europe/Paris');


    // Nous récupérons les informations passées dans le fichier "inscription.php" dans la balise <form> et l'attribut action="script_inscription.php" 
    // Les informations sont récupéré avec variable superglobale $_POST 

    if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['login']) && isset($_POST['mdp']) && isset($_POST['mdp2']))
    {
        if (!empty($_POST['nom'] && $_POST['prenom'] && $_POST['email'] && $_POST['login'] && $_POST['mdp'] && $_POST['mdp2']))
        {
            $user_nom = htmlspecialchars($_POST['nom']);         // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur a pu rentrer et nous aide d'éviter la faille XSS  
            $user_prenom = htmlspecialchars($_POST['prenom']);
            $user_email = htmlspecialchars($_POST['email']);
            $user_login = htmlspecialchars($_POST['login']);
            $user_mdp = htmlspecialchars($_POST['mdp']);
            $user_mdp2 = htmlspecialchars($_POST['mdp2']);
        }
        else
        {
            echo "<h4> Veuillez remplir tous les champs ! </h4>";
            header("refresh:2; url=inscription.php");  // refresh:2 signifie que après 5 secondes l'utilisateur sera redirigé sur la page inscription.php. 
            exit;
        }
    }

        

    // Un mot de passe ne doit jamais être stocké en clair : il doit être crypté à l'aide d'un algorithme de cryptage afin que 
    // sa valeur ne puisse être lue. La fonction password_hash() permet d’utiliser des algorithmes de cryptage en PHP:  
    // D'abord on vérifie la validité du mot de passe:
    
    if ($user_mdp == $user_mdp2)
    {
        $user_mdp = password_hash($user_mdp, PASSWORD_DEFAULT);  // Si le mot de passe est valide, on fait cryptage avec fonction password_hash()
    }
    else
    {
        echo "<h4> Le mot de passe n'est pas identique  </h4>";
        header("refresh:2; url=inscription.php");
        exit;
    }


    // Vérification si adresse mail saisi par utilisateur déjà existe dans la base de données ou non ?
    // Pour cela d'abord on va se connecter à la base de données: 
    require "connection_bdd.php";
    
    // Ensuite on construit la requête SELECT pour aller chercher les colonnes user_email et user_login qui se trouvent dans table "users" :
    $req = "SELECT user_email, user_login FROM users" ;
    
    // Grace à méthode query() on exécute notre requête et on ramene les colonnes user_email et et user_login et on les mets dans l'objet $result :
    // On peut également écrire notre requête comme ça :  $result = $db->query("SELECT user_email, user_login FROM users")
    $result = $db->query($req)  or  die(print_r($db->errorInfo()));    // Pour repérer l'erreur SQL en PHP on utilise le code die(print_r($db->errorInfo())) 

    // Grace à la méthode "rowCount()" nous pouvons connaitre le nombre de lignes retournées par la requête
    $nbLigne = $result->rowCount(); 
    
    if ($nbLigne >= 1)
    {
        while ($row = $result->fetch(PDO::FETCH_OBJ))    // Grace à la méthode fetch() on choisit 1er ligne de la colonne user_mail et user_login et on les mets dans l'objet $row                                            
        {                                                // Avec la boucle "while" on choisit 2eme, 3eme, etc... lignes de la colonne user_mail et user_login et on les mets dans l'objet $row    
            if ($row->user_email == $user_email)
            {
                echo "<h4> Cette adresse mail déjà existe. Choisissez une autre! </h4>";
                header("refresh:2; url=inscription.php");
                exit;
            } 
            if ($row->user_login == $user_login)
            {
                echo "<h4> Ce login déjà existe. Choisissez une autre! </h4>";
                header("refresh:2; url=inscription.php");
                exit;
            } 
        }
    }        
      

    // Construction de la requête INSERT:
    // On insere pas la valeurs pour la colonne "login_fail" car dans base de données on a bien défini que cette colonne accepte la valeur 0
    $requete = $db->prepare("INSERT INTO users (user_nom, user_prenom, user_email, user_login, user_mdp, user_inscription, user_connexion, user_role) 
    VALUES (:user_nom, :user_prenom, :user_email, :user_login, :user_mdp, :user_inscription, :user_connexion, :user_role)");


    if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $user_email))  // Vérification la validité de format de l'adresse mail avec REGEX en utilisant la fonction preg_match() qui renvoie True or False:
    {
        // Association des valeurs aux marqueurs via méthode "bindValue()"
        $requete->bindValue(':user_nom', $user_nom, PDO::PARAM_STR);
        $requete->bindValue(':user_prenom', $user_prenom, PDO::PARAM_STR);
        $requete->bindValue(':user_email', $user_email, PDO::PARAM_STR);
        $requete->bindValue(':user_login', $user_login, PDO::PARAM_STR);
        $requete->bindValue(':user_mdp', $user_mdp, PDO::PARAM_STR);
    
        $time = new DateTime();   // On utilise l'objet DateTime() pour montrer la date d'inscription et l'heure du dernier connexion du client
        $date = $time->format("Y/m/d H:i:s"); 


        $requete->bindValue(':user_inscription', $date, PDO::PARAM_STR);  
        $requete->bindValue(':user_connexion', $date, PDO::PARAM_STR);
        
        if($user_login=="Nijat")
        {
            $requete->bindValue(':user_role', 'admin', PDO::PARAM_STR);
        }
        else
        {
            $requete->bindValue(':user_role', 'client', PDO::PARAM_STR);
        }
        

        // Exécution de la requête
        $requete->execute();
        
        //Libèration la connection au serveur de BDD
        $requete->closeCursor();

        //Redirection vers la page acceuil.php 
        header("Location: acceuil.php");
        exit;
    }

    else
    {
        echo "<h4> L'adresse mail n'a pas bon format! </h4>";
        header("refresh:2; url=inscription.php");
        exit;
    }
    


    
?>



