<?php 
    
    // On va enregistrer l'heure de dernier connexion du client. Pour obtenir la bonne heure, il faut configurer la valeur 
    // de l'option datetime_zone sur la valeur Europe/Paris.
    // Donc, il faut ajouter l'instruction date_default_timezone_set("Europe/Paris") dans vos scripts avant toute manipulation de dates :
    date_default_timezone_set('Europe/Paris');


    // Nous récupérons les informations passées dans le fichier "login.php" dans la balise <form> et l'attribut action="script_login.php" 
    // Les informations sont récupéré avec variable superglobale $_POST 

    if (isset($_POST['login']) && isset($_POST['mdp']))
    {
        if (!empty($_POST['login']) && !empty($_POST['mdp']))
        {
            $user_login = htmlspecialchars($_POST['login']);  // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur a pu rentrer et nous aide d'éviter la faille XSS  
            $user_mdp = htmlspecialchars($_POST['mdp']);
        }
        else
        {
            echo "<h4> Veuillez remplir tous les champs ! </h4>";
            header("refresh:2; url=login.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page login.php. 
            exit;
        }
    }
    else
    {
        echo "<h4> Veuillez remplir tous les champs ! </h4>";
        header("refresh:2; url=login.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page login.php. 
        exit;
    }
        


    /* Vérification: Est-ce que le mot de passe saisi par utilisateur déjà existe dans la base de données ou non ?
    D'abord on doit récupérer le mot de passe hashé de l'utilisateur qui se trouve dans la base de données.
    Pour cela, on va se connecter à la base de données:    */
    require "connection_bdd.php";
    
    // Puis on fait préparation de la requête SELECT avec la fonction prepare(): 
    $requete = $db->prepare('SELECT user_mdp, user_blocked FROM users WHERE user_login = :user_login');

    // Execution de requête:
    $requete->execute(array(':user_login' => $user_login));
    
    // $resultat est un array associatif qui contient:  1. user_mdp et sa valeur;    2. user_blocked et sa valeur
    $resultat = $requete->fetch();  

    // Pour vérifier si un mot de passe saisi est bien celui enregistré en base, on utilise la fonction password_verify() qui renvoie True ou False :
    $PasswordCorrect = password_verify($user_mdp, $resultat['user_mdp']);   

    if ($PasswordCorrect && empty($resultat['user_blocked']))
    {
        //Construction de la requête UPDATE pour mettre à jour la date et l'heure du dernier connexion de l'utilisateur:
        $requete = $db->prepare("UPDATE users SET user_connexion=:user_connexion WHERE user_login=:user_login");

        // On utilise l'objet DateTime() pour montrer la date et l'heure du dernier connexion du client: 
        $time = new DateTime();  
        $date = $time->format("Y/m/d H:i:s"); 
        
        // Association des valeurs aux marqueurs via méthode "bindValue()"
        $requete->bindValue(':user_connexion', $date, PDO::PARAM_STR);
        $requete->bindValue(':user_login', $user_login, PDO::PARAM_STR);

        // Exécution de la requête
        $requete->execute(); 

        // Création d'une session :
        session_start();
        
        // On va créer 2 variable SESSION:  $_SESSION['login']  et  $_SESSION['role']
        $_SESSION['login'] = $user_login;

        $requete = $db->prepare('SELECT user_role FROM users WHERE user_login=:user_login');
        $requete->execute(array(':user_login' => $user_login));
        $resultat = $requete->fetch();  
        
        if($resultat['user_role']=='admin')
        {
            $_SESSION['role'] = "admin";
        }
        else
        {
            $_SESSION['role'] = "client";
        }

        echo '<h4>  Bonjour ' . $_SESSION['role'] ." ". $_SESSION['login'] . '<br> Vous êtes connecté ! </h4>';
		header("refresh:2; url=index.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page index.php.
		exit;
    }

    else 
    {
        $requete = $db->prepare('SELECT login_fail, user_blocked, unblock_time FROM users WHERE user_login=:user_login');
        
        // Association des valeurs aux marqueurs via méthode "bindValue()"
        $requete->bindValue(':user_login', $user_login, PDO::PARAM_STR);

        // Exécution de la requête
        $requete->execute(); 
        
        $resultat = $requete->fetch(); 
        /* $resultat est un tableau (array) associatif qui contient : 
        1. login_fail et sa valeur, 
        2. user_blocked et sa valeur,
        3. unblock_time et sa valeur.   */
         
        // On augmente le nombre de login_fail à chaque fois que l'utilisateur rate s'identifier :
        $login_fail = $resultat['login_fail'] + 1;  

        if($login_fail < 4)   
        {
            // Ensuite on enregistre nouvelle valeur de login_fail dans la base de donnée: 
			$requete = $db->prepare('UPDATE users SET login_fail=:login_fail WHERE user_login=:user_login');
			
			// Association des valeurs aux marqueurs via méthode "bindValue()"
            $requete->bindValue(':login_fail', $login_fail, PDO::PARAM_INT);
            $requete->bindValue(':user_login', $user_login, PDO::PARAM_STR);
            
            // Exécution de la requête
            $requete->execute(); 
           
            echo "<h4> Mauvais identifiant ou mot de passe ! </h4>";
            header("refresh:2; url=login.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page login.php.
            exit;
        }
		else   // Si l'utilisateur 3 fois ne saisit pas son mot de passe correctement on le bloque 
		{
			if(empty($resultat['user_blocked']))
			{
				$requete = $db->prepare('UPDATE users SET user_blocked=:user_blocked, unblock_time=:unblock_time WHERE user_login=:user_login');
				
				// Association des valeurs aux marqueurs via méthode "bindValue()"
				$unblock_time = time() + (1*1*2*60);	// La fonction time() renvoie l'heure actuelle en nombre de secondes depuis l'époque Unix (1er janvier 1970 00:00:00 GMT).

				$requete->bindValue(':user_blocked', $user_login, PDO::PARAM_STR);
				$requete->bindValue(':unblock_time', $unblock_time, PDO::PARAM_INT);
				$requete->bindValue(':user_login', $user_login, PDO::PARAM_STR);

				// Exécution de la requête
				$requete->execute(); 

				echo "<h4> Vous êtes bloqué pour 2 minutes! </h4>";
				header("refresh:2; url=login.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page login.php.
				exit;
			}
			else
			{
				$current_time = time();	  // La fonction time() renvoie l'heure actuelle en nombre de secondes depuis l'époque Unix (1er janvier 1970 00:00:00 GMT).

				if($resultat['unblock_time'] < $current_time)
				{
					$requete = $db->prepare('UPDATE users SET login_fail=:login_fail, user_blocked=:user_blocked, unblock_time=:unblock_time WHERE user_login=:user_login');
					
					$requete->bindValue(':login_fail', 0, PDO::PARAM_INT);
					$requete->bindValue(':user_blocked', null, PDO::PARAM_STR);
					$requete->bindValue(':unblock_time', null, PDO::PARAM_INT);
					$requete->bindValue(':user_login', $user_login, PDO::PARAM_STR);
					
					$requete->execute();

					echo "<h4> Maintenant vous êtes débloqué ! <br> Veuillez réessayer de vous connecter ! </h4>";
					header("refresh:3; url=login.php");  // refresh:2 signifie que après  secondes l'utilisateur sera redirigé sur la page login.php.
					exit;
				}
				else
				{
					echo "<h4> Vous êtes bloqué pour 2 minutes! </h4>";
					header("refresh:2; url=login.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page login.php.
					exit;
				}
			}
		} 
    }  
    

    $requete->closeCursor();

    

?>



