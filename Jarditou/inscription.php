<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">

        <!-- Responsive web design -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap CSS 4.5.3 import from CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <title> Inscription </title>
    </head>


     <!-- PAGE HEAD -->
     <?php
        if (file_exists("header.php"))
        {
            include("header.php");
        }
        else
        {
            echo "file 'header.php' n'existe pas";
        }
    ?>


    <!-- PAGE MAIN CONTENT -->
    <!-- Vos coordonnées -->
    <div class="container border border-dark px-4 pb-4"  style="width:62%"> 
        <div class="col p-0">
            <p class="pt-3"> <sup>*</sup> Ces zones sont obligatoires </p>
            <h1 class="pb-3"> Formulaire d'inscription </h1>
            

            <form  method="post"  action="script_inscription.php">
                <div class="form-group">
                    <label for="surname"> Nom <sup>*</sup> </label>
                    <input type="text"  class="form-control"  id="surname"  name="nom"  placeholder="Veuillez saisir votre nom">
                </div>

                <div class="form-group">
                    <label for="name"> Prénom <sup>*</sup> </label>
                    <input type="text"  class="form-control"  id="name"  name="prenom"  placeholder="Veuillez saisir votre prénom">
                </div>

                <div class="form-group">
                    <label for="mail"> Adresse mail <sup>*</sup> </label>
                    <input type="email"  class="form-control"  id="mail"  name="email"  placeholder="dave.loper@afpa.fr">
                </div>

                <div class="form-group">
                    <label for="username"> Login <sup>*</sup> </label>
                    <input type="text"  class="form-control"  id="username"  name="login">
                </div>

                <div class="form-group">
                    <label for="code"> Mot de passe <sup>*</sup> </label>
                    <input type="password"  class="form-control"  id="code"  name="mdp">
                </div>

                <div class="form-group">
                    <label for="confirmer"> Confirmer le mot de passe <sup>*</sup> </label>
                    <input type="password"  class="form-control"  id="confirmer"  name="mdp2">
                </div>

                <!-- VALIDER button -->
                <div class="form-group" style="margin-top:40px">
                    <input type="submit"  value="Valider"  style="float:left; margin-left:300px; padding:10px 35px; border-radius:10px; background-color:green; color:white"> 
                </div>
            </form>


             <!-- ANNULER button -->
            <a href="acceuil.php"> 
                <button style="margin-left:100px; padding:10px 30px; border-radius:10px; background-color:red; color:white"> Annuler </button> 
            </a> 
        </div>
    </div>



    <!-- PAGE FOOT -->
    <?php
        include("footer.php")
    ?>

     
     

</html>
            