<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
       
        <!-- Responsive design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
       
        <title> Inscription </title>

        <!-- Bootstrap CDN link -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    
        <!-- Fichier CSS -->
        <link rel="stylesheet" href="css/style.css">

        <!-- JQuery Google CDN: -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>


    <body>
        <main>
            <!-- PAGE HEAD -->
            <?php
                if (file_exists("header_accueil.php"))
                {
                include("header_accueil.php");
                }
                else
                {
                echo "file 'header_accueil.php' n'existe pas";
                }
            ?>

            <!-- PAGE CONTENT -->
            <div class="container" id="form_inscription">
                <br><br><br>
                <div class="slogan_2">
                    <h3> Veuillez saisir vos coordonnées : </h3>
                </div>

                <form action="script_inscription.php" method="POST" autocomplete="off" class="form_connect">
                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="name"> Nom <sup>*</sup> </label> 
                        <input id="name" type="text" class="form-control" name="userNom" required>
                    </div>

                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="surname"> Prénom <sup>*</sup> </label> 
                        <input id="surname" type="text" class="form-control" name="userPrenom" required>
                    </div>

                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="RaisonSociale"> Raison sociale <sup>*</sup> </label> 
                        <input id="RaisonSociale" type="text" class="form-control" name="societe" required>
                    </div>

                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="siren"> N° SIREN <sup>*</sup> </label>
                        <input id="siren" type="text" class="form-control" name="numSiren" required>
                    </div>
                    
                    <p>  Vous êtes : </p>
                    <div class="form-check form-check-inline ml-3"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">  
                        <input class="form-check-input" id="customer" type="radio" name="userRole" value="client" checked>
                        <label class="form-check-label" for="customer"> Client </label>
                    </div>
                    <div class="form-check form-check-inline"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <input class="form-check-input" id="supplier" type="radio" name="userRole" value="fournisseur">
                        <label class="form-check-label" for="supplier"> Fournisseur </label>
                    </div>

                    <br><br>

                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                    <label for="address"> Adresse <sup>*</sup> </label>
                    <input id="address" type="text" class="form-control" name="adr" required>
                    </div>

                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="ZipCode"> Code postal <sup>*</sup> </label>
                        <input id="ZipCode" type="number" class="form-control" name="codePostal" maxlength="5" required>   <!-- maxlength="5" Code postal doit contenir au maximum 5 chiffres -->
                    </div>

                    <!-- API Code Postaux -->
                    <ul id="liste_CP"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <li data-vicopo="#city, #ZipCode" data-vicopo-click='{"#ZipCode": "ZipCode", "#city": "city"}'>
                            <strong data-vicopo-code-postal></strong>
                            <span data-vicopo-ville></span>
                        </li>
                    </ul>
                    
                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="city"> Ville <sup>*</sup> </label>
                        <input id="city" type="text" class="form-control" name="ville" required>
                    </div>

                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="state"> Pays <sup>*</sup> </label>
                        <input id="state" type="text" class="form-control" name="pays" required>
                    </div>

                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="email"> Email <sup>*</sup> </label>
                        <input id="email" type="email" class="form-control" name="mail" required>
                    </div>

                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="code"> Mot de passe <sup>*</sup> </label>
                        <input id="code" type="password" class="form-control" name="mdp" required>
                    </div>

                    <div class="form-group"  class="col-1 col-sm-8 col-md-9 col-lg-10 col-xl-11">
                        <label for="confirmer"> Confirmer le mot de passe <sup>*</sup> </label>
                        <input id="confirmer" type="password" class="form-control" name="mdp2" required>
                    </div>
                    
                    <!-- Les boutons <Valider> et <Annuler> -->
                    <div class="boutons">
                        <button type="submit" class="btn btn-success mr-3"> Valider </button>
                        <a href="connexion.php"> 
                            <input type="button" class="btn btn-danger" value="Annuler"> 
                        </a>
                    </div>
                </form>
            </div>
        </main>
         

        <!-- Bootstrap Jquery, Popper -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
 

        <!-- fichier Javascript RegExp -->
        <script src="javascript/RegExp2.js"> </script>


        <!-- fichier Javascript Vicopo pour autocompletion de Code Postaux/villes dans le formulaire de page inscription-->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="javascript/vicopo.min.js"></script>
                
    </body>
</html>