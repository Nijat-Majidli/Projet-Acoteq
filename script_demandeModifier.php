<?php
    // Pour utiliser la variable superglobale "$_SESSION" il faut ajouter le fonction session_start() tout au début de la page:
    session_start();  

    /* On va enregistrer la date et l'heure de modification de la demande. Pour obtenir la bonne date et l'heure, 
    il faut configurer la valeur de l'option <<datetime_zone>> sur la valeur Europe/Paris. Donc, il faut ajouter 
    l'instruction <<date_default_timezone_set("Europe/Paris");>> dans nos scripts avant toute manipulation de dates.  */
    date_default_timezone_set('Europe/Paris');

    /* Nous récupérons les informations passées dans le fichier "demandeModifier.php" dans la balise <form>  et 
    l'attribut action="script_demandeModifier.php".   Les informations sont récupéré avec variable superglobale $_POST     */
    if(isset($_POST['demande_id']) && isset($_POST['titre']) && isset($_POST['description']) && isset($_POST['budget']) && isset($_POST['etat']))
    {
        if (!empty($_POST['demande_id'] && $_POST['titre'] && $_POST['description'] && $_POST['budget'] && $_POST['etat']))
        {
            // La fonction "htmlspecialchars" rend inoffensives les balises HTML que le visiteur peux rentrer et nous aide d'éviter la faille XSS  
            $demande_id = htmlspecialchars($_POST['demande_id']);
            $demande_titre = htmlspecialchars($_POST['titre']);
            $demande_description = htmlspecialchars($_POST['description']);
            $demande_budget = htmlspecialchars($_POST['budget']);
            $demande_etat = htmlspecialchars($_POST['etat']); 

            // Connection à la base de données 
            require "connection_bdd.php";

            // Construction de la requête INSERT avec la méthode prepare() sans injection SQL
            $requete = $db->prepare("UPDATE demande SET demande_titre=:demande_titre, demande_description=:demande_description, 
            demande_budget=:demande_budget, demande_etat=:demande_etat, demande_modification=:demande_modification, 
            demande_publication=:demande_publication WHERE demande_id=:demande_id");

            // Association des valeurs aux marqueurs via méthode "bindValue()"
            $requete->bindValue(':demande_titre', $demande_titre, PDO::PARAM_STR);
            $requete->bindValue(':demande_description', $demande_description, PDO::PARAM_STR);
            $requete->bindValue(':demande_budget', doubleval($demande_budget), PDO::PARAM_INT); // fonction doubleval() convertit le type de variable en décimale
            $requete->bindValue(':demande_etat', $demande_etat, PDO::PARAM_STR);
            $requete->bindValue(':demande_id', $demande_id, PDO::PARAM_INT);

            // On utilise l'objet DateTime() pour enregistrer la date et l'heure de creation et publication de demande dans la base de données
            $time = new DateTime();   
            $date = $time->format("Y/m/d H:i:s"); 

            $requete->bindValue(':demande_modification', $date, PDO::PARAM_STR);

            if($demande_etat=="publié")
            {
                $requete->bindValue(':demande_publication', $date, PDO::PARAM_STR);
            }
            else if ($demande_etat=="sauvegardé")
            {
                $requete->bindValue(':demande_publication', NULL, PDO::PARAM_STR);   
            }

            // Exécution de la requête
            $requete->execute();

            //Libèration la connection au serveur de BDD
            $requete->closeCursor();


            /*  Si un client publie sa demande on envoie un email de notification à tous les fournisseurs. 
            Pour cela on construit la requête SELECT pour aller chercher la colonne demande_etat et demande_notification dans la table "demande":     */
            $req="SELECT demande_etat, demande_notification FROM demande WHERE demande_etat='publié' AND demande_notification='non envoyé'";

            /* Avec la méthode query() on exécute notre requête et on ramene les colonnes demande_etat et demande_notification 
            on les mets dans l'objet $result.     */
            $result = $db->query($req)  or  die(print_r($db->errorInfo()));  // Pour repérer l'erreur SQL en PHP on utilise le code die(print_r($db->errorInfo())) 

            // Grace à la méthode "rowCount()" on peut connaitre le nombre de lignes retournées par la requête
            $nbLigne = $result->rowCount(); 
            
            if ($nbLigne >= 1)
            {
                while ($row = $result->fetch(PDO::FETCH_OBJ))   // Grace à la méthode fetch() on choisit 1er ligne de la colonne demande_etat et demande_notification et on les mets dans l'objet $row                                            
                {                                               // Ensuite avec la boucle "while" on choisit 2eme, 3eme, etc.. lignes de la colonne demande_etat et demande_notification et on les mets dans l'objet $row  
                    
                    $requete="SELECT user_email FROM fournisseur";
                    $resultat = $db->query($requete)  or  die(print_r($db->errorInfo()));
                    $nbLigne = $resultat->rowCount(); 
            
                    if ($nbLigne >= 1)
                    {
                        while ($row2 = $resultat->fetch(PDO::FETCH_OBJ))
                        {
                            // Avec la méthode mail() on envoie un email de notification aux fournisseurs:
                            mail($row2->user_email, "Nouvelle demande", "Bonjour, Une nouvelle demande a été publié!", array('MIME-Version' => '1.0', 'Content-Type' => 'text/html; charset=utf-8', "From"=>"contact@acoteq.com", "X-Mailer" => "PHP/".phpversion()));
                        } 
                    }
                }
            }
            
            //Libèration la connection au serveur de BDD
            $result->closeCursor();

            // Puis on met à jour la colonne demande_notification:
            $req = "UPDATE demande SET demande_notification=:demande_notification WHERE user_email=:user_email AND demande_etat='publié'";
            $requete = $db->prepare($req);

            // Execution de requête:
            $requete->execute(array(':demande_notification' => 'envoyé', ':user_email' => $_SESSION['email']));

            //Libèration la connection au serveur de BDD
            $result->closeCursor();
        }
        else
        {
            echo "<h4> Veuillez remplir tous les champs ! </h4>";
            header("refresh:2; url=demande.php");  // refresh:2 signifie qu'après 2 secondes l'utilisateur sera redirigé vers la page demande.php
            exit;
        }
    }
    else
    {
        echo "<h4> Veuillez remplir tous les champs ! </h4>";
        header("refresh:2; url=demande.php");  
        exit;
    }       
    

    /* Lorsque le formulaire (page demande.php) est soumis, on récupère les informations sur le fichier téléchargé via la variable 
    superglobale $_FILES, qui se comporte comme un tableau associatif PHP.
    Le problème principal de l'upload d'un fichier est la sécurité. On doit tout d'abord vérifier 2 points basiques :
    1. Le fichier a-t-il bien été téléchargé (upload) ?
    2. La taille du fichier ne dépasse-t-elle pas la taille autorisée? ?
    3. Le type du fichier envoyé par l'utilisateur est-il celui attendu (image, document Word, PDF etc...) ?        */

    if(isset($_FILES['fichier']))
    { 
        // D'abord on vérifie la taille (en octets) du fichier. Ici la maximale taille autorisée est 5 000 000 octets (soit 5Mo) :
        $taille_maxi = 5000000;
        $taille_fichier = filesize($_FILES['fichier']['tmp_name']);  // La fonction filesize() retourne la taille d'un fichier.

        if($taille_fichier > $taille_maxi)
        {
            echo "<h4> La taille du fichier ne peut pas dépasser 5Mo ! </h4>";
            header("refresh:2; url=demande.php");  
            exit;
        }
        
        // Maintenant on vérifie le type du fichier. Pour cela on met les types autorisés dans un tableau nommé $extensions_valide :  
        $extensions_valide = array(".pdf", ".doc", ".docx", ".txt");
        
        /* Ensuite on récupére l'extension du fichier téléchargé par l'utilisateur à l'aide de la fonction strrchr() qui nous renvoie 
        le string à partir de '.' qui correspond à l'extension du fichier    */
        $extension_fichier = strtolower(strrchr($_FILES['fichier']['name'], '.'));   // strtolower() transforme tous les caractères en minuscules

        if (in_array($extension_fichier, $extensions_valide))
        {
            /* Si le type du fichier est parmi ceux autorisés, donc OK, on va pouvoir déplacer et renommer le fichier.
            Par défaut, le fichier téléchargé est stocké dans le répertoire tmp (temporary) de notre serveur Wamp dans C:/wamp/tmp 
            Mais ce fichier devra se trouver dans un répertoire de notre projet, il faut donc le déplacer.
            Donc, via la méthode "move_uploded_file()" on va déplacer notre fichier vers le répertoire "fichiers" de notre projet */   
            
            $nom_fichier = basename($_FILES['fichier']['name']);  // fonction basename() renvoie le nom de fichier à partir du chemin spécifié
            
            /* Le nom du fichier peut lui aussi poser problème. Nous devons donc nous occuper de lui car s'il contient des accents, 
            caractères spéciaux, espaces, ça peut poser problème. Nous allons donc "formater" le nom du fichier avant de l'uploader.    
            Ici on remplace les lettres accentutées par les non accentuées dans $nom_fichier:    */
            $nom_fichier = strtr($nom_fichier, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); 
            
            // On déplace le fichier de répertoire C:/wamp/tmp vers un répertoire nommé fichiers/ :
            move_uploaded_file($_FILES["fichier"]["tmp_name"], "fichiers/".$nom_fichier);   
            
            echo '<h4> Votre fichier a été téléchargé avec succès! </h4> ';
            header("refresh:2; url=demandeSaved.php");   
            exit;

            // GOOGLE DRIVE API
            // require_once 'google-api-php-client/src/Google_Client.php';
            // require_once 'google-api-php-client/src/contrib/Google_DriveService.php';
            // // this is google client library and you got to include it in order to use it.
            
            // $client = new Google_Client();
            
            // // Get your credentials from the APIs Console
            // // add your client id and client secret(you got it when you created your account)
            // $client->setClientId('502716320845-kcro1rqgqd346olb2ak1794rf9v3ge7a.apps.googleusercontent.com');
            // $client->setClientSecret('6oNP9d3FxjbklN3WRQNuB6g1');
            
            // //This is standard Uri for installed applications,may change in web applications.
            // $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
            
            // $client->setScopes(array('https://www.googleapis.com/auth/drive'));
            
            // $service = new Google_DriveService($client);

            // $authUrl = $client->createAuthUrl();
            // //Request authorization
            // print "Please visit:\n$authUrl\n\n";
            // print "Please enter the auth code:\n";
            // $authCode = trim(fgets(STDIN));
            // // Exchange authorization code for access token
            // $accessToken = $client->authenticate($authCode);
            // $client->setAccessToken($accessToken);
            // //Insert a file
            // $file = new Google_DriveFile();
            // $file->setTitle('My document');
            // $file->setDescription('A test document');
            // $file->setMimeType('text/plain');
            
            // $data = file_get_contents('document.txt');
            
            // $createdFile = $service->files->insert($file, array(
            //     'data' => $data,
            //     'mimeType' => 'text/plain',
            //     ));
            
            // print_r($createdFile);
        }
        else 
        {
            // Le type n'est pas autorisé, donc ERREUR
            echo "<h4> Vous devez télécharger un fichier de type pdf, doc, docx ou txt'; </h4>"; 
            header("refresh:2; url=demande.php");   // refresh:2 signifie qu'après 2 secondes l'utilisateur sera redirigé vers la page demande.php
            exit;
        }     
    } 
    else
    {
        echo "<h4> Veuillez télécharger un fichier </h4>"; 
        header("refresh:2; url=demande.php");   
        exit;
    }
    
    


?>