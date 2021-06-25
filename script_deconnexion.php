<?php

    session_start();

    $_SESSION['email'] = "";
    $_SESSION['role'] = "";
    $_SESSION['user_siren'] = "";


    unset($_SESSION['email']);
    unset($_SESSION['role']);
    unset($_SESSION['user_siren']);


    if (ini_get("session.use_cookies")) 
    {
        setcookie(session_name(), '', time()-1);
    }


    session_destroy();
    

    echo "<h4> Vous êtes déconnecté ! </h4>";
    header("refresh:2; url=accueil.php");  
    exit;
   


    /* 
    Lignes 5-7 : on affecte une valeur vide aux variables de session.
    
    Lignes 10-12 : suppression des variables de session.
    
    Lignes 15-18 : via la fonction setcookie(), on fait expirer en termes de date le cookie qui concerne le nom de la session. 
    Ceci n’est valide que dans le cas où les sessions sont gérées par cookies (comportement par défaut de PHP), d’où la condition.
    
    Ligne 21 : la fonction session_destroy() détruit le reste de la session.     
    */

?>