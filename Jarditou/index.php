<?php 
    session_start();  
    /* ATTENTION
    Il est impératif d'utiliser la fonction session_start() au début de chaque fichier PHP dans lequel on manipulera cette 
    variable et avant tout envoi de requêtes HTTP, c'est-à-dire avant tout echo ou quoi que ce soit d'autre : rien ne doit 
    avoir encore été écrit/envoyé à la page web.  */

    if (isset($_SESSION['login']))
    {
        echo 'Bonjour '. $_SESSION['login'] ;
    }
    else
    {
        echo "<h4> Cette page nécessite une identification </h4>";
        header("refresh:2; url=login.php");  // refresh:2 signifie que après 2 secondes l'utilisateur sera redirigé sur la page login.php. 
        exit;
    }
?>



<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">

        <!-- Responsive web design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap CSS 4.5.3 import from CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" 
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <title> Index </title>
    </head>


    <!-- PAGE HEAD -->
<?php
    if (file_exists("header.php"))
    {
        include("header.php");
    }
    else
    {
        echo "fichier 'header.php' n'existe pas";
    }
?>


    <!-- PAGE MAIN CONTENT -->
    <div class="container"> 
        <div class="table-responsive" style="margin-top:20px;"> 
            <!-- AJOUTER button -->
            <a href="ajout.php"> 
                <button style="float:left; margin:0 0 10px 840px; padding:10px 30px; border-radius:10px; background-color:green; color:white"> Ajouter </button> 
            </a> 
            <!-- DECONNEXION button -->
            <a href="script_deconnexion.php"> 
                <button style="margin:0 0 10px 20px; padding:10px 10px; border-radius:10px; background-color:blue; color:white"> Déconnexion </button> 
            </a> 
            <!-- Table of products -->
            <table class="table table-bordered table-striped"> 
                <thead class="thead-light" >
                    <tr class="font-weight-bolder">
                        <th scope="col"> Photo </th>
                        <th scope="col"> ID </th>
                        <th scope="col"> Référence </th>
                        <th scope="col"> Libellé </th>
                        <th scope="col"> Description </th>
                        <th scope="col"> Prix </th>
                        <th scope="col"> Stock </th>
                        <th scope="col"> Couleur </th>
                        <th scope="col"> Date_d'ajout </th>
                        <th scope="col"> Date_modification </th>
                        <th scope="col"> Bloqué </th>
                    </tr>
                </thead>
                <tbody>         
                <!-- Code PHP -->
<?php
                // Connéxion à la base de données 
                require "connection_bdd.php";
                
                // On construit la requête SELECT : 
                $requete = "SELECT * FROM produits";

                // Exécution de requête via la méthode "query()" et on met le résultat retourné dans une variable-objet $result
                $result = $db->query($requete);    
                
                // On peut aussi écrire $result = $db->query("SELECT * FROM produits")

                // Grace à la méthode "rowCount()" on peut compter le nombre de lignes retournées par la requête
                $nbLigne = $result->rowCount(); 
                
                if($nbLigne >= 1)
                {
                    while ($row = $result->fetch(PDO::FETCH_OBJ))  // Grace à méthode fetch() on choisit le 1er ligne de chaque colonne et la mets dans l'objet $row
                    {                                              // Avec la boucle "while" on choisit 2eme, 3eme, etc... lignes de chaque colonne et les mets dans l'objet $row
?>
                        <tr>
                            <td class="table-warning"  style="width:130px">
                                <div>
                                    <img  src="<?php echo "public/image/"; echo $row->pro_id; echo "."; echo $row->pro_photo ?>"  alt="imgproduit"  class="img-fluid">
                                </div>
                            </td> 
                            <td> 
                                <div> 
                                    <?php  echo $row->pro_id; ?>  
                                </div>
                            </td>
                            <td> 
                                <div> 
                                    <?php  echo $row->pro_ref; ?>
                                </div>
                            </td>
                            <td class="table-warning"> 
                                <div>  
                                    <a href="detail.php?pro_id=<?php echo $row->pro_id ?>">  <?php echo $row->pro_libelle; ?>  </a>
                                </div>
                            </td>
                            <td> 
                                <div> 
                                    <?php  echo $row->pro_description; ?>
                                </div>
                            </td>
                            <td> 
                                <div> 
                                    <?php  echo $row->pro_prix; ?>
                                </div>
                            </td>
                            <td> 
                                <div> 
                                    <?php  echo $row->pro_stock; ?>
                                </div>
                            </td>
                            <td> 
                                <div> 
                                    <?php  echo $row->pro_couleur; ?>
                                </div>
                            </td>
                            <td> 
                                <div> 
                                    <?php  echo $row->pro_d_ajout; ?>
                                </div>
                            </td>
                            <td> 
                                <div> 
                                    <?php  echo $row->pro_d_modif; ?>
                                </div>
                            </td>
                            <td> 
                                <div> 
                                    <?php  echo $row->pro_bloque; ?>
                                </div>
                            </td>
                        </tr>
<?php
                    }
                    
                    // Sert à finir proprement une série de fetch(), libère la connection au serveur de BDD
                    $result->closeCursor();
                }
?>    
                </tbody>
            </table>
        </div>
    </div>



<!-- PAGE FOOT -->
<?php
    include("footer.php")
?>



</html>
            