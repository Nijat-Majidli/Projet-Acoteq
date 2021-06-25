<?php
     
    // On récupérer le paramétre demande_id transmit en GET par la page "demandeDetail.php" et on le met dans la variable $demande_id :
    if(isset($_GET['demande_id']) && !empty($_GET['demande_id']))
    {
        // La fonction "trim()" efface les espaces blancs au début et à la fin d'une chaîne.
        // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS  
        $demande_id = trim(htmlspecialchars((int)$_GET['demande_id']));   // Pour vérifier que $_GET['demande_id'] contient bien un nombre entier, on utilise (int) pour convertir la variable GET en type entier. 

        // Connéxion à la base de données 
        require "connection_bdd.php";
                        
        // On construit la requête DELETE avec la méthode prepare() sans injection SQL : 
        $requete = $db->prepare("DELETE FROM demande WHERE demande_id=:demande_id");

        // Association valeur au marqueur :email via méthode "bindValue()"
        $requete->bindValue(':demande_id', $demande_id, PDO::PARAM_INT);

        // On exécute la requête
        $requete->execute();

        // Libèration la connection au serveur de BDD
        $requete->closeCursor();

        echo '<h4> Votre demande a été supprimé avec succès! </h4> ';
        header("refresh:2; url=client.php");   // refresh:2 signifie qu'après 2 secondes l'utilisateur sera redirigé vers la page client.php
        exit;
    }
    else
    {
        echo "<h4> Veuillez remplir tous les champs ! </h4>";
        header("refresh:2; url=client.php");  
        exit;
    }
          

?>