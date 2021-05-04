<?php

    if (!function_exists('Verify'))
    {
        function Verify()
        {
            static $i=0;
            global $user_login;

            if($i<3)
            {
                echo "<h4> Mauvais identifiant ou mot de passe ! </h4>";
                $i++;
                header("refresh:2; url=login.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page login.php.
                exit;
            }

            //Construction de la requête INSERT:
            $requete = $db->prepare('UPDATE users SET user_bloque=:user_bloque WHERE user_login=:user_login');
                
            // Association des valeurs aux marqueurs via méthode "bindValue()"
            $requete->bindValue(':user_bloque', $user_login, PDO::PARAM_STR);
            $requete->bindValue(':user_login', $user_login, PDO::PARAM_STR);
        
            // Exécution de la requête
            $requete->execute(); 

            echo "<h4> Vous êtes bloqué ! </h4>";
            header("refresh:2; url=login.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page login.php. 
            exit;
        }
    }
    




?>